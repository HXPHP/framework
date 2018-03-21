<?php

namespace Tests;

use HXPHP\System\App;
use HXPHP\System\Configs\Config;
use HXPHP\System\Loader;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $baseURI = 'https://hxphp.dev';

    protected $app;

    public function __construct()
    {
        parent::__construct();

        $this->createApplication();
    }

    public function createApplication()
    {
        $config = new Config();
        $loader = Loader::getInstance();

        $loader->addLoadedInstance('Config',['object' => $config]);
        $loader->addLoadedInstance('Loader',['object' => $loader]);

        $this->app = new App($config,$loader);

        return $this;
    }

    public function getConfigs() :Config
    {
        return $this->app->configs;
    }
}
