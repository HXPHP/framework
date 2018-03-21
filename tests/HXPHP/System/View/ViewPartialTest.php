<?php

namespace Tests\System\View;

use HXPHP\System\View\ViewPartial;
use Tests\BaseTestCase;

class ViewPartialTest extends BaseTestCase
{
    protected $partial;

    public function __construct()
    {
        parent::__construct();

        $this->partial = new ViewPartial($this->getConfigs());
    }

    public function testSetPartialDir()
    {
        $this->partial->setPartialsDir('pedacos');
        $this->assertEquals('pedacos', $this->partial->getPartialsDir());
    }

    public function testSetSubfolder()
    {
        $this->partial->setSubfolder('pedacos');
        $this->assertEquals('pedacos', $this->partial->getSubfolder());
    }

}
