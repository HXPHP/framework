<?php

namespace Tests\System\Configs\Modules;

use HXPHP\System\Configs\Modules\Views;
use Tests\BaseTestCase;

class ViewsTest extends BaseTestCase
{
    /** @var Views $view */
    protected $view;

    public function __construct()
    {
        parent::__construct();
        $this->view = new Views();
    }

    public function testChangeFileExtension()
    {
        $this->view->setExtension('.html');
        $this->assertEquals('.html', $this->view->getExtension());
    }

    public function testChangePartialFolder()
    {
        $this->view->setPartialsDir('_partial');
        $this->assertEquals('_partial', $this->view->getPartialsDir());
    }

    public function testChangeViewsFolder()
    {
        $this->view->setViewDirectory('telas');
        $this->assertEquals('telas', $this->view->getViewDirectory());
    }

    public function testAssetsConfigToView()
    {
        $this->view->setAssets('css','arquivos/css/teste.css');
        $this->assertEquals('arquivos/css/teste.css', $this->view->getAssets()['css'][0]);

        $this->view->setAssets('js','arquivos/js/teste.js');
        $this->assertEquals('arquivos/js/teste.js', $this->view->getAssets()['js'][0]);
    }

    public function testTemplateDisable()
    {
        $this->view->enableTemplate(false);
        $this->assertInternalType('bool', $this->view->isTemplate());
        $this->assertEquals(false, $this->view->isTemplate());
    }

    public function testSetTemplateHeader()
    {
        $this->view->setHeader('views/template/header.phtml');
        $this->assertEquals('views/template/header.phtml', $this->view->getHeader());
    }

    public function testSetTemplateFooter()
    {
        $this->view->setFooter('views/template/footer.phtml');
        $this->assertEquals('views/template/footer.phtml', $this->view->getFooter());
    }

    public function testChangeTitle()
    {
        $this->view->setTitle('Titulo Teste para Teste');
        $this->assertEquals('Titulo Teste para Teste', $this->view->getTitle());
    }

    public function testChangeVarViewPrefix()
    {
        $this->view->setViewVarsPrefix('hx');
        $this->assertEquals('hx', $this->view->getViewVarsPrefix());
    }

}
