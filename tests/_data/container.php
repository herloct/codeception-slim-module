<?php

use Codeception\Util\ReflectionHelper;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Container;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

function files_to_array(array $files)
{
    $result = [];
    foreach ($files as $fieldName => $uploadedFile) {
        /* @var $uploadedFile UploadedFile|array */
        if (is_array($uploadedFile)) {
            $result[$fieldName] = files_to_array($uploadedFile);
        } else {
            $result[$fieldName] = [
                'name' => $uploadedFile->getClientFilename(),
                'tmp_name' => ReflectionHelper::readPrivateProperty($uploadedFile, 'file'),
                'size' => $uploadedFile->getSize(),
                'type' => $uploadedFile->getClientMediaType(),
                'error' => $uploadedFile->getError(),
            ];
        }
    }

    return $result;
}

class NewRequest extends Request {}

class NewResponse extends Response {}

$container = new Container([
    'request' => function (ContainerInterface $c) {
        return NewRequest::createFromEnvironment($c->get('environment'));
    },

    'response' => function (ContainerInterface $c) {
        $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new NewResponse(200, $headers);

        return $response->withProtocolVersion($c->get('settings')['httpVersion']);
    },

    App::class => function (ContainerInterface $c) {
        $app = new App($c);

        $app->get(
            '/api/ping',
            function (ServerRequestInterface $request, ResponseInterface $response) {
                $body = $response->getBody();
                $body->write(json_encode([
                    'ack' => time()
                ]));

                return $response->withHeader('content-type', 'application/json')
                    ->withBody($body);
            }
        );

        $app->map(
            ['GET', 'POST', 'PUT', 'DELETE'],
            '/rest',
            function (ServerRequestInterface $request, ResponseInterface $response) {
                $tokenHeaderValue = null;
                $tokenHeader = $request->getHeader('X-Auth-Token');
                if (count($tokenHeader) > 0) {
                    $tokenHeaderValue = $tokenHeader[0];
                }

                $responseClass = get_class($response);
                $requestClass  = get_class($request);

                $body = $response->getBody();
                $body->write(json_encode([
                    'requestMethod' => $request->getMethod(),
                    'requestUri' => $request->getRequestTarget(),
                    'queryParams' => $request->getQueryParams(),
                    'formParams' => $request->getParsedBody(),
                    'rawBody' => (string)$request->getBody(),
                    'headers' => $request->getHeaders(),
                    'X-Auth-Token' => $tokenHeaderValue,
                    'files' => files_to_array($request->getUploadedFiles()),
                    'responseClass' => $responseClass,
                    'requestClass' => $requestClass,
                ]));

                return $response->withHeader('content-type', 'application/json')
                    ->withBody($body);
            }
        );

        $app->get(
            '/',
            function (ServerRequestInterface $request, ResponseInterface $response) {
                $body = $response->getBody();
                $body->write(file_get_contents(__DIR__.'/template.phtml'));

                return $response->withHeader('content-type', 'text/html')
                    ->withBody($body);
            }
        );

        return $app;
    }
]);

return $container;
