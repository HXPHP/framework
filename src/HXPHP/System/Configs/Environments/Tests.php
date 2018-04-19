<?php

namespace HXPHP\System\Configs\Environments;

use HXPHP\System\Configs\AbstractEnvironment;

/**
 * Class Tests.
 */
class Tests extends AbstractEnvironment
{
    public function __construct()
    {
        parent::__construct();

        ini_set('display_errors', 1);
    }
}
