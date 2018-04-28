<?php

namespace HXPHP\System;

use HXPHP\System\Configs\Config;
use HXPHP\System\Controller\Core;

/**
 * Class Controller.
 *
 * @property Config $configs
 */
class Controller extends Core
{
    public function __construct(Config $configs = null)
    {
        parent::__construct($configs);

        foreach (Loader::getLoadedStatic('', true) as $class) {
            $name = strtolower($class['name']);
            $this->$name = $class['object'];
        }
    }

    public function getRelativeURL(string $URL, bool $controller = true): string
    {
        $path = true === $controller ? $this->path.DIRECTORY_SEPARATOR : $this->subfolder;

        return $this->configs->baseURI.$path.$URL;
    }

    public function printRelativeURL(string $URL, bool $controller = true)
    {
        $path = true === $controller ? $this->path.DIRECTORY_SEPARATOR : $this->subfolder;

        echo $this->configs->baseURI.$path.$URL;
    }
}
