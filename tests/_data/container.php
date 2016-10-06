<?php

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    App::class => function (ContainerInterface $c) {
        $app = new App();

        $app->get('/simple-get', function (ServerRequestInterface $request, ResponseInterface $response) {
            $body = $response->getBody();
            $body->write('Hello there');

            return $response->withBody($body);
        });

        $app->get('/complex-get', function (ServerRequestInterface $request, ResponseInterface $response) {
            $body = $response->getBody();
            $body->write(json_encode([
                'query' => $request->getQueryParams(),
                'headers' => $request->getHeaders()
            ]));

            return $response->withHeader('content-type', 'application/json')
                ->withBody($body);
        });

        $app->get('/complex-get/{id}/{name}', function (ServerRequestInterface $request, ResponseInterface $response) {
            $body = $response->getBody();
            $body->write(json_encode([
                'attributes' => $request->getAttributes(),
                'query' => $request->getQueryParams(),
                'headers' => $request->getHeaders()
            ]));

            return $response->withHeader('content-type', 'application/json')
                ->withBody($body);
        });

        $app->map(['POST', 'PUT', 'DELETE'], '/complex/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
            $body = $response->getBody();
            $body->write(json_encode([
                'attributes' => $request->getAttributes(),
                'query' => $request->getQueryParams(),
                'headers' => $request->getHeaders(),
                'parsed_body' => $request->getParsedBody()
            ]));

            return $response->withHeader('content-type', 'application/json')
                ->withBody($body);
        });

        return $app;
    }
]);

return $builder->build();
