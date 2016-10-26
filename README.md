# Codeception Slim Module

[![Master Build Status](https://travis-ci.org/herloct/codeception-slim-module.svg?branch=1.0.4)](https://travis-ci.org/herloct/codeception-slim-module)
[![Packagist Stable Version](https://img.shields.io/packagist/v/herloct/codeception-slim-module.svg)](https://packagist.org/packages/herloct/codeception-slim-module)
[![Packagist License](https://img.shields.io/packagist/l/herloct/codeception-slim-module.svg)](https://packagist.org/packages/herloct/codeception-slim-module)
[![Libraries.io for GitHub](https://img.shields.io/librariesio/github/herloct/codeception-slim-module.svg)](https://libraries.io/github/herloct/codeception-slim-module)

This module allows you to run tests inside [Slim 3 Microframework](http://www.slimframework.com/).  
Based on [ZendExpressive Module](https://github.com/Codeception/Codeception/blob/2.2/src/Codeception/Module/ZendExpressive.php).

## Install

Via commandline:

```shell
composer require --dev herloct/codeception-slim-module
```

Via `composer.json`:

```json
{
  "require-dev": {
    "herloct/codeception-slim-module": "^1.0"
  }
}
```

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
* client - [BrowserKit](http://symfony.com/doc/current/components/browser_kit.html) client

## Todos

* Add more acceptance/functional tests other than REST.
