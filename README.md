# Codeception Slim Module

[![Build Status](https://travis-ci.org/herloct/codeception-slim-module.svg?branch=master)](https://travis-ci.org/herloct/codeception-slim-module)

This module allows you to run tests inside Slim 3 Microframework.  
Based on [ZendExpressive Module](https://github.com/Codeception/Codeception/blob/2.2/src/Codeception/Module/ZendExpressive.php).

## Config

* container: relative path to file which returns Container.

```yaml
\Herloct\Codeception\Module\Slim:
  container: path/to/container.php
```

Minimum `container.app` contents.

```php
require __DIR__.'/vendor/autoload.php';

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;
use Slim\App;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    App::class => function (ContainerInterface $c) {
        $app = new App();

        // routes and middlewares here

        return $app;
    }
]);

return $builder->build();
```

## API

* application -  instance of `\Slim\App`
* container - instance of `\Interop\Container\ContainerInterface`
* client - BrowserKit client

## Todos

* Add more acceptance/functional tests other than REST.
