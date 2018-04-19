<?php

namespace Tests\System\Configs;

use HXPHP\System\Configs\Environments\Development;
use Tests\BaseTestCase;

class AbstractEnvironmentTest extends BaseTestCase
{
    public function testLoadFrameworkModules()
    {
        $aet = new Development();
        $aet->loadModules();

        $this->assertInstanceOf('HXPHP\System\Configs\Modules\Mail',$aet->mail);
    }

}