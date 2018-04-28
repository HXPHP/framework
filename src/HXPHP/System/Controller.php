<?php

namespace HXPHP\System;

use HXPHP\System\Configs\Config;
use HXPHP\System\Controller\Core;

class Controller extends Core
{
    public function __construct(Config $configs = null)
    {
        parent::__construct($configs);
    }
}
