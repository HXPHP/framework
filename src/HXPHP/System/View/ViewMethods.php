<?php

namespace HXPHP\System\View;

/**
 * Trait ViewMethods
 * @package HXPHP\System\View
 *
 * @property ViewTemplate $template
 * @property ViewAssets $assets
 */
trait ViewMethods
{
    public function setTitle(string $title)
    {
        $this->template->setTitle($title);
        return $this;
    }

    /**
     * Define um conjunto de variáveis para a VIEW.
     *
     * @param array $vars Array com variáveis
     */
    public function setVars(array $vars): self
    {
        $this->template->setVars($vars);
        return $this;
    }

    /**
     * Define uma variável única para a VIEW.
     *
     * @param string $name  Nome do índice
     * @param string $value Valor
     */
    public function setVar(string $name, $value): self
    {
        $this->template->setVar($name,$value);
        return $this;
    }

    /**
     * @return array
     */
    public function getVars(string $var = null): array
    {
        return $this->template->getVars($var);
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader(string $header)
    {
        $this->template->setHeader($header);
        return $this;
    }

    /**
     * @param string $name
     * @param string $file
     * @return $this
     */
    public function setTemplatePart(string $name, string $file)
    {
        $this->template->setTemplatePart($name, $file);
        return $this;
    }

    /**
     * @param string $footer
     * @return $this
     */
    public function setFooter(string $footer)
    {
        $this->template->setFooter($footer);
        return $this;
    }

    /**
     * @param bool $template
     * @return $this
     */
    public function setTemplate(bool $template)
    {
        $this->template->setTemplate($template);
        return $this;
    }

    /**
     * @param string $type
     * @param $assets array|string
     * @return $this
     */
    public function setAssets(string $type, $assets)
    {
        $this->assets->setAssets($type, $assets);
        return $this;
    }

}