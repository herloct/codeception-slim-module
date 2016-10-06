<?php

declare(strict_types=1);

namespace Herloct\Codeception\Lib\Connector;

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
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
            ->withUri($uri);
        if ($request->getContent() !== null) {
            $body = new RequestBody();
            $body->write($request->getContent());
            $slimRequest = $slimRequest
                ->withBody($body);
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
}
