<?php

namespace Herloct\Codeception\Module;

use Codeception\Configuration;
use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Herloct\Codeception\Lib\Connector\Slim as Connector;
use Interop\Container\ContainerInterface;
use Slim\App;

final class Slim extends Framework
{
    protected $requiredFields = ['container'];

    /**
     * @var ContainerInterface
     */
    public $container;

    /**
     * @var App
     */
    public $app;

    public function _initialize()
    {
        $cwd = getcwd();
        chdir(Configuration::projectDir());
        $this->container = include Configuration::projectDir() . $this->config['container'];
        chdir($cwd);

        $this->app = $this->container->get(App::class);

        parent::_initialize();
    }

    public function _before(TestInterface $test)
    {
        $this->client = new Connector();
        $this->client->setApp($this->app);

        parent::_before($test);
    }

    public function _after(TestInterface $test)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        parent::_after($test);
    }
}
