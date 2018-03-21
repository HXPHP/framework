<?php

namespace HXPHP\System\Configs\Modules;

class Views
{
    protected $extension   = '.phtml';

    protected $partialsDir = 'partials';

    protected $directory   = 'views';

    protected $subfolder   = 'default';

    protected $assets      = [
        'css' => [],
        'js'  => [],
    ];

    protected $template     = true;

    /**
     * @var array
     */
    protected $template_tree = [
        'header' => 'header',
        'view' => 'index',
        'footer' => 'footer'
    ];

    /**
     * @var array
     */
    protected $template_tree_order = [
        'header' => '',
        'view' => '',
        'footer' => ''
    ];

    protected $templateFolder = '';

    protected $header       = 'header';

    protected $footer       = 'footer';

    protected $title        = 'HXPHP';

    protected $viewVarsPrefix = 'view';

    /**
     * @param string $extension
     * @return $this
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $partialsDir
     * @return $this
     */
    public function setPartialsDir(string $partialsDir)
    {
        $this->partialsDir = $partialsDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartialsDir(): string
    {
        return $this->partialsDir;
    }

    /**
     * @param string $directory
     * @return $this
     */
    public function setViewDirectory(string $directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewDirectory(): string
    {
        return $this->directory;
    }


    /**
     * @param string $type
     * @param $assets array|string
     * @return $this
     */
    public function setAssets(string $type, $assets)
    {
        if(in_array($type,['js','css']))
        {
            (is_array($assets)) ?
                $this->assets[$type] = array_merge($this->assets[$type], $assets) :
                array_push($this->assets[$type], $assets);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    /**
     * @param bool $template
     * @return $this
     */
    public function enableTemplate(bool $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTemplate(): bool
    {
        return $this->template;
    }

    /**
     * @param string $name
     * @param string $file
     * @return $this
     */
    public function setTemplatePart(string $name, string $file)
    {
        $this->template_tree[$name] = $file;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateTree(): array
    {
        return $this->template_tree;
    }

    /**
     * @param array $template_tree_order
     * @return $this
     */
    public function setTemplateTreeOrder(array $template_tree_order)
    {
        $this->template_tree_order = $template_tree_order;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateTreeOrder(): array
    {
        return $this->template_tree_order;
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader(string $header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $footer
     * @return $this
     */
    public function setFooter(string $footer)
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return $this->footer;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $viewVarsPrefix
     * @return $this
     */
    public function setViewVarsPrefix(string $viewVarsPrefix)
    {
        $this->viewVarsPrefix = $viewVarsPrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewVarsPrefix(): string
    {
        return $this->viewVarsPrefix;
    }

}
