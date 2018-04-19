<?php

namespace Tests\System\Configs;

use HXPHP\System\Configs\Config;
use Tests\BaseTestCase;

class ConfigTest extends BaseTestCase
{
    /** @var Config */
    protected $configs;

    public function __construct()
    {
        parent::__construct();

        $this->configs = $this->getConfigs();
    }

    public function testGetCurrentEnv()
    {
        $this->assertEquals('tests', $this->configs->getCurrentEnv());
    }

    public function testSetCurrentEnv()
    {
        $this->configs->define->setDefaultEnv('development');
        $this->assertEquals('development', $this->configs->getCurrentEnv());
    }

    public function testSetAuthModuleConfigParameter()
    {
        $this->configs->auth->after_login = '/hxphp/test/';
        $this->configs->auth->after_logout = '/hxphp/login/';

        $this->assertEquals('/hxphp/test/', $this->configs->auth->after_login);
        $this->assertEquals('/hxphp/login/', $this->configs->auth->after_logout);
    }

    public function testSetCustomConfigPropertyValue()
    {
        $this->configs->title = 'Meu Teste Automatizado HXPHP';

        $this->assertEquals('Meu Teste Automatizado HXPHP', $this->configs->title);
    }

    public function testGetConfigPropertyValue()
    {
        $this->assertEquals('HXPHP Framework', $this->configs->title);
    }
}
