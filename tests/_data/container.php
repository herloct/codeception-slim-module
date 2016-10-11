<?php

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

function files_to_array(array $files)
{
    $result = [];
    foreach ($files as $fieldName => $uploadedFile) {
        /**
         * @var $uploadedFile \Slim\Http\UploadedFile|array
         */
        if (is_array($uploadedFile)) {
            $result[$fieldName] = files_to_array($uploadedFile);
        } else {
            $result[$fieldName] = [
                'name' => $uploadedFile->getClientFilename(),
                'tmp_name' => \Codeception\Util\ReflectionHelper::readPrivateProperty($uploadedFile, 'file'),
                'size' => $uploadedFile->getSize(),
                'type' => $uploadedFile->getClientMediaType(),
                'error' => $uploadedFile->getError(),
            ];
        }
    }
    return $result;
}

$builder = new ContainerBuilder();

$builder->addDefinitions([
    App::class => function (ContainerInterface $c) {
        $app = new App();

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

        $app->post(
          '/echo-parsed-body',
          function (ServerRequestInterface $request, ResponseInterface $response) {
              $body = $request->getParsedBody();

              return $response->withJson($body);
          }
        );

        return $app;
    }
]);

return $builder->build();
