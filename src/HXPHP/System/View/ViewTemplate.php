<?php

namespace HXPHP\System\View;

use HXPHP\System\Configs\Config;

/**
 * Class ViewTemplate.
 */
class ViewTemplate
{
    /**
     * @var bool
     */
    protected $template = true;

    /**
     * @var array
     */
    protected $template_tree = [];

    /**
     * @var array
     */
    protected $template_tree_order = [
        'header' => '',
        'view'   => '',
        'footer' => '',
    ];

    /**
     * @var string
     */
    protected $file = 'index';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var string
     */
    protected $subfolder = '';

    /**
     * @var string
     */
    protected $path = 'index';

    /**
     * @var Config
     */
    protected $configs;

    public function __construct(Config $config)
    {
        $this->configs = $config;
    }

    /**
     * Define o título da página.
     *
     * @param string $title Título da página
     */
    public function setTitle(string $title): self
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
     * @param string $subfolder
     *
     * @return $this
     */
    public function setSubfolder(string $subfolder)
    {
        $this->subfolder = $subfolder;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubfolder(): string
    {
        return $this->subfolder;
    }

    /**
     * Define a pasta da view.
     *
     * @param string $path Caminho da View
     */
    public function setPath(string $path, bool $overwrite = false): self
    {
        $this->path = false === $overwrite ? $this->subfolder.$path : $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Define se o arquivo é miolo (Inclusão de Cabeçalho e Rodapé) ou único.
     *
     * @param bool $template Template ON/OFF
     */
    public function setTemplate(bool $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Define o cabeçalho da view.
     *
     * @param string $header Cabeçalho da View
     */
    public function setHeader(string $header, bool $overwrite = false): self
    {
        $this->template_tree['header'] = false === $overwrite ? $this->subfolder.$header : $header;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->template_tree['header'] ?? '';
    }

    /**
     * Define o rodapé da view.
     *
     * @param string $footer Rodapé da View
     */
    public function setFooter(string $footer, bool $overwrite = false): self
    {
        $this->template_tree['footer'] = false === $overwrite ? $this->subfolder.$footer : $footer;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->template_tree['footer'] ?? '';
    }

    /**
     * @param array $template_tree_order
     */
    public function setTemplateTreeOrder(array $template_tree_order)
    {
        $this->template_tree_order = $template_tree_order;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateTreeOrder()
    {
        return $this->template_tree_order;
    }

    /**
     * Define uma parte custumizada da view.
     *
     * @param string $name Parte da View
     */
    public function setTemplatePart(string $name, string $file): self
    {
        $this->template_tree[$name] = $file;

        return $this;
    }

    /**
     * Define uma parte custumizada da view.
     *
     * @param string $name Parte da View
     */
    public function getTemplatePart(string $name)
    {
        if (isset($this->template_tree[$name])) {
            return $this->template_tree[$name];
        }
    }

    /**
     * Define as partes custumizadas da view.
     *
     * @param string $name Parte da View
     */
    public function setTemplateParts(array $parts): self
    {
        foreach ($parts as $name => $file) {
            $this->setTemplatePart($name, $file);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateTree()
    {
        return $this->template_tree;
    }

    /**
     * Define o arquivo da view.
     *
     * @param string $file Arquivo da View
     */
    public function setFile(string $file): self
    {
        $this->template_tree['view'] = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->template_tree['view'] ?? '';
    }

    /**
     * Define uma variável única para a VIEW.
     *
     * @param string $name  Nome do índice
     * @param string $value Valor
     */
    public function setVar(string $name, $value): self
    {
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * Define um conjunto de variáveis para a VIEW.
     *
     * @param array $vars Array com variáveis
     */
    public function setVars(array $vars): self
    {
        $this->vars = array_merge($this->vars, $vars);

        return $this;
    }

    /**
     * @return string|null|array
     */
    public function getVars(string $var = null)
    {
        if ($var) {
            if (isset($this->vars[$var])) {
                return $this->vars[$var];
            }
        }

        return $this->vars;
    }
}
