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
        $configs = new Config();
        $this->app = new App($configs);

        return $this;
    }

    public function getConfigs() :Config
    {
        return $this->app->configs;
    }
}
