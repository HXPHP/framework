<?php

namespace Tests\System\View;

use HXPHP\System\View\ViewAssets;
use Tests\BaseTestCase;

class ViewAssetsTest extends BaseTestCase
{
    /**
     * @var ViewAssets
     */
    protected $assert;

    public function __construct()
    {
        parent::__construct();

        $this->assert = new ViewAssets($this->getConfigs());
    }

    public function testSetAssetsWithString()
    {
        $this->assert->setAssets('css','bootstrap/css/bootstrap.min.css');
        $this->assertEquals('bootstrap/css/bootstrap.min.css',$this->assert->getAssets('css')[0]);
    }

    public function testSetAssetsWithArray()
    {
        $this->assert->setAssets('css',[
            'bootstrap/css/bootstrap.min.css',
            'foundation/css/foundation.min.css'
        ]);
        $this->assertEquals('foundation/css/foundation.min.css',$this->assert->getAssets('css')[1]);
    }

    public function testCreateAssetHTMLTag()
    {
        $this->assert->setAssets('css','bootstrap/css/bootstrap.min.css');
        $this->assertEquals('<link type="text/css" rel="stylesheet" href="bootstrap/css/bootstrap.min.css">'."\n\r",$this->assert->assets('css',$this->assert->getAssets('css')));
    }

}
