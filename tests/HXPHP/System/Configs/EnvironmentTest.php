<?php

namespace Tests\System\Configs;

use HXPHP\System\Configs\DefineEnvironment;
use HXPHP\System\Configs\Environment;
use Tests\BaseTestCase;

class EnvironmentTest extends BaseTestCase
{
    public function testDefaultEnvironment()
    {
        $env = new Environment(new DefineEnvironment());

        $this->assertInstanceOf('HXPHP\System\Configs\Environments\Tests', $env->tests);
    }

    public function testChangeEnvironmentToDevelopment()
    {
        $env = new Environment(new DefineEnvironment());
        $env->add('development');

        $this->assertInstanceOf('HXPHP\System\Configs\Environments\Development', $env->development);
    }

    public function testChangeEnvironmentToProduction()
    {
        $env = new Environment(new DefineEnvironment());
        $env->add('production');

        $this->assertInstanceOf('HXPHP\System\Configs\Environments\Production', $env->production);
    }
}
