<?php

namespace Tests\System;

use HXPHP\System\Configs\Config;
use HXPHP\System\Controller;
use HXPHP\System\Loader;
use Tests\BaseTestCase;

class ControllerTest extends BaseTestCase
{
    protected $controller;

    public function __construct()
    {
        parent::__construct();
        $this->controller = new Controller();
    }

    public function testControllerGetCurrentEnv()
    {
        $this->assertEquals('tests', $this->controller->configs->getCurrentEnv());
    }

    public function testControllerSetCurrentEnv()
    {
        $this->controller->configs->define->setDefaultEnv('development');
        $this->assertEquals('development', $this->controller->configs->getCurrentEnv());
    }

    public function testControllerGetLoaderModule()
    {
        $loader = $this->controller->getLoaderClass('Loader');
        $this->assertInstanceOf('HXPHP\System\Loader', $loader);
    }

    public function testControlerSetAuthModule()
    {
        /** @var Config $config */
        $config = Loader::getLoadedStatic('Config');

        $config->env->tests->auth->setURLs('/hxphp/home/', '/hxphp/login/');

        $auth = $this->controller->load('Services\Auth',
            $config->auth->after_login,
            $config->auth->after_logout,
            true);

        $this->assertInstanceOf('HXPHP\System\Services\Auth\Auth', $auth);
    }
}
