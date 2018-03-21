<?php

namespace HXPHP\System\Configs;

trait CurrentEnviroment
{
    public function getCurrentEnvironment(): string
    {
        $default = new DefineEnvironment();
        return $default->getDefault();
    }
}