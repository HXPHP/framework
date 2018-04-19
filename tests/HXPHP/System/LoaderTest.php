<?php

namespace Tests\System;

use HXPHP\System\Loader;
use HXPHP\System\View\ViewTemplate;
use Tests\BaseTestCase;

class LoaderTest extends BaseTestCase
{

    public function testGetLoaderConfigInstance()
    {
        /** @var Loader $loader */
        $loader = Loader::getLoadedStatic('Loader');

        $config = $loader->getLoaded('Config');

        $this->assertInstanceOf('HXPHP\System\Configs\Config', $config);
    }

    public function testGetLoaderConfigStaticInstance()
    {
        $this->assertInstanceOf('HXPHP\System\Configs\Config', Loader::getLoadedStatic('Config'));
    }

    public function testLoaderGlobalConfigInstance()
    {
        $this->assertInstanceOf('HXPHP\System\Configs\GlobalConfig',Loader::loadStatic('core','Configs\GlobalConfig'));
    }

    public function testGetGlobalConfigInstance()
    {
        $this->assertInstanceOf('HXPHP\System\Configs\GlobalConfig',Loader::getLoadedStatic('GlobalConfig'));
    }

    public function testAddLoadedInstanceToLoader()
    {
        $view = new ViewTemplate($this->getConfigs());

        /** @var Loader $loader */
        $loader = Loader::getLoadedStatic('Loader');
        $loader->addLoadedInstance('ViewTemplate',['object' => $view]);

        $this->assertInstanceOf('HXPHP\System\View\ViewTemplate',$loader->getLoaded('ViewTemplate'));
    }

    public function testStaticAddLoadedInstanceToLoader()
    {
        $view = new ViewTemplate($this->getConfigs());
        Loader::addLoadedInstanceStatic('ViewTemplate',['object' => $view]);
        $this->assertInstanceOf('HXPHP\System\View\ViewTemplate',Loader::getLoadedStatic('ViewTemplate'));
    }

    public function testGetViewTemplateInstanceWithDetail()
    {
        $this->assertInternalType('array',Loader::getLoadedStatic('ViewTemplate',true));
    }

}
