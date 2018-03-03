<?php
namespace HXPHP\System;

use HXPHP\System\Controller\Controller;

class Controller extends Controller
{
    public function __construct(Config $configs = null)
    {
        parent::__construct($configs);
    }
}