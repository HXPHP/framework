<?php

namespace HXPHP\System\Configs;

class Bootstrap
{
    const DS = DIRECTORY_SEPARATOR;

    public function __construct()
    {
        $this->setEnvVariables();
    }

    private function setEnvVariables()
    {
        if (defined('ROOT_PATH')) {
            putenv('ROOT_PATH='.ROOT_PATH.static::DS);
            putenv('APP_PATH='.getenv('ROOT_PATH').'app'.static::DS);
            putenv('TEMPLATES_PATH='.getenv('ROOT_PATH').'templates'.static::DS);
            putenv('HXPHP_VERSION=3.1.0');
        }
    }
}
