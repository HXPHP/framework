<?php

namespace Tests\System\Configs\Modules;

use Tests\BaseTestCase;

class AuthTest extends BaseTestCase
{
    public function testSetConfigurationToModuleByFunction()
    {
        $config = $this->getConfigs();
        $config->env->tests->auth->setURLs('/hxphp/home', '/hxphp/login');

        $this->assertEquals('/hxphp/home', $config->env->tests->auth->after_login['default']);
        $this->assertEquals('/hxphp/login', $config->env->tests->auth->after_logout['default']);
    }

    public function testSetConfigurationToModuleByProperty()
    {
        $config = $this->getConfigs();

        $config->env->tests->auth->after_login['default'] = '/hxphp/home/admin';
        $config->env->tests->auth->after_logout['default'] = '/hxphp/home/login';

        $this->assertEquals('/hxphp/home/admin', $config->env->tests->auth->after_login['default']);
        $this->assertEquals('/hxphp/home/login', $config->env->tests->auth->after_logout['default']);
    }
}
