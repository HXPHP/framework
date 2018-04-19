<?php

namespace Tests\System\View;

use HXPHP\System\View\ViewTemplate;
use Tests\BaseTestCase;

class ViewTemplateTest extends BaseTestCase
{
    /**
     * @var ViewTemplate
     */
    protected $template;

    public function __construct()
    {
        parent::__construct();

        $this->template = new ViewTemplate($this->getConfigs());
    }

    public function testSetCustomTitle()
    {
        $this->template->setTitle('Titulo Texte HXPHP');
        $this->assertEquals('Titulo Texte HXPHP',$this->template->getTitle());
    }

    public function testSetCustomSubfolder()
    {
        $this->template->setSubfolder('template');
        $this->assertEquals('template',$this->template->getSubfolder());
    }

    public function testSetCustomPath()
    {
        $this->template->setPath('controller3');
        $this->assertEquals('controller3',$this->template->getPath());
    }

    public function testSetTemplateEnabled()
    {
        $this->template->setTemplate(false);

        $this->assertInternalType('bool',$this->template->getTemplate());
        $this->assertEquals(false ,$this->template->getTemplate());
    }

    public function testSetTemplateHeader()
    {
        $this->template->setHeader('header2');
        $this->assertEquals('header2',$this->template->getHeader());
    }

    public function testSetTemplateFooter()
    {
        $this->template->setFooter('footer');
        $this->assertEquals('footer',$this->template->getFooter());
    }

    public function testSetTemplateOrder()
    {
        $this->template->setTemplateTreeOrder([
            'header' => '',
            'footer' => '',
            'view' => ''
        ]);
        $this->assertInternalType('array',$this->template->getTemplateTreeOrder());
    }

    public function testSetTemplatePart()
    {
        $this->template->setTemplatePart('menu','menuArquivo');
        $this->assertEquals('menuArquivo',$this->template->getTemplatePart('menu'));
    }

    public function testGetTemplateTree()
    {
        $this->assertInternalType('array',$this->template->getTemplateTree());
    }

    public function testSetTemplateViewFile()
    {
        $this->template->setFile('arquivo');

        $this->assertEquals('arquivo',$this->template->getFile());
    }

    public function testSetTemplateVarWithString()
    {
        $this->template->setVar('coisas','guardadas');
        $this->assertEquals('guardadas', $this->template->getVars('coisas'));
    }

    public function testSetTemplateVarWithArray()
    {
        $this->template->setVars(['mais_coisas' => 'continuam_guardadas']);
        $this->assertEquals('continuam_guardadas', $this->template->getVars('mais_coisas'));
    }
}