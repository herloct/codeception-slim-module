<?php

namespace Herloct\Codeception\Lib\Connector;

use Psr\Http\Message\UploadedFileInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Slim\Http\Uri;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;

final class Slim extends Client
{
    /**
     * @var App
     */
    private $app;

    /**
     * @param App $app
     */
    public function setApp(App $app)
    {
        $this->app = $app;
    }

    /**
     * Makes a request.
     *
     * @param BrowserKitRequest $request An origin request instance
     *
     * @return BrowserKitResponse An origin response instance
     */
    protected function doRequest($request)
    {
        $environment = Environment::mock($request->getServer());
        $uri = Uri::createFromString($request->getUri());

        $slimRequest = Request::createFromEnvironment($environment)
            ->withMethod($request->getMethod())
            ->withUri($uri)
            ->withUploadedFiles($this->convertFiles($request->getFiles()));
        if ($request->getContent() !== null) {
            $body = new RequestBody();
            $body->write($request->getContent());
            $slimRequest = $slimRequest
                ->withBody($body);
        }

        $parsed = [];
        if ($request->getMethod() !== 'GET') {
            $parsed = $request->getParameters();
        }

        // make sure we do not overwrite a request with a parsed body
        if ($parsed !== null && !$slimRequest->getParsedBody()) {
            $slimRequest = $slimRequest
              ->withParsedBody($parsed);
        }

        $slimHeaders = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);

        $slimResponse = $this->app->process(
            $slimRequest,
            (new Response(200, $slimHeaders))
                ->withProtocolVersion($this->app->getContainer()->get('settings')['httpVersion'])
        );

        return new BrowserKitResponse(
            (string) $slimResponse->getBody(),
            $slimResponse->getStatusCode(),
            $slimResponse->getHeaders()
        );
    }

    private function convertFiles(array $files)
    {
        $fileObjects = [];
        foreach ($files as $fieldName => $file) {
            if ($file instanceof UploadedFileInterface) {
                $fileObjects[$fieldName] = $file;
            } elseif (!isset($file['tmp_name']) && !isset($file['name'])) {
                $fileObjects[$fieldName] = $this->convertFiles($file);
            } else {
                $fileObjects[$fieldName] = new UploadedFile(
                    $file['tmp_name'],
                    $file['name'],
                    $file['type'],
                    $file['size'],
                    $file['error']
                );
            }
        }
        return $fileObjects;
    }
}
