# Codeception Slim Module

[![Latest Stable Version](https://poser.pugx.org/herloct/codeception-slim-module/v/stable)](https://packagist.org/packages/herloct/codeception-slim-module)
[![Latest Unstable Version](https://poser.pugx.org/herloct/codeception-slim-module/v/unstable)](https://packagist.org/packages/herloct/codeception-slim-module)
[![Build Status](https://travis-ci.org/herloct/codeception-slim-module.svg?branch=master)](https://travis-ci.org/herloct/codeception-slim-module)
[![License](https://poser.pugx.org/herloct/codeception-slim-module/license)](https://packagist.org/packages/herloct/codeception-slim-module)
[![Libraries.io for GitHub](https://img.shields.io/librariesio/github/herloct/codeception-slim-module.svg)](https://libraries.io/github/herloct/codeception-slim-module)

This module allows you to run tests inside Slim 3 Microframework.  
Based on [ZendExpressive Module](https://github.com/Codeception/Codeception/blob/2.2/src/Codeception/Module/ZendExpressive.php).

## Config

* container: relative path to file which returns Container.

```yaml
\Herloct\Codeception\Module\Slim:
  container: path/to/container.php
```

Minimum `container.php` contents.

```php
require __DIR__.'/vendor/autoload.php';

use Interop\Container\ContainerInterface;
use Slim\App;
use Slim\Container;

$container = new Container([
    App::class => function (ContainerInterface $c) {
        $app = new App($c);

        // routes and middlewares here

        return $app;
    }
]);

return $container;
```

## API

* application -  instance of `\Slim\App`
* container - instance of `\Interop\Container\ContainerInterface`
* client - BrowserKit client

## Todos

* Add more acceptance/functional tests other than REST.
