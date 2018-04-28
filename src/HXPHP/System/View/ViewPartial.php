<?php

namespace HXPHP\System\View;

use HXPHP\System\Configs\AbstractEnvironment;
use HXPHP\System\Configs\Config;
use HXPHP\System\Configs\Modules\Views;

/**
 * Class ViewPartial.
 */
class ViewPartial
{
    protected $partialsDir = 'partial';

    protected $subfolder = '';

    /**
     * @var Config
     */
    private $configs;

    public function __construct(Config $config)
    {
        $this->configs = $config;
    }

    public function partial(string $view, array $params = [])
    {
        /** @var AbstractEnvironment $env */
        $env = $this->configs->getCurrentEnv();

        /** @var Views $viewConfig */
        $viewConfig = $this->configs->env->$env->views;

        if (!empty($params)) {
            extract($params, EXTR_PREFIX_ALL, $viewConfig->getViewVarsPrefix());
        }

        $viewsExt = $viewConfig->getExtension();

        $viewFile = $this->partialsDir.'_'.$view.$viewsExt;

        if (!file_exists($viewFile)) {
            throw new \Exception("Erro fatal: A view <'$viewFile'> não foi encontrada. Por favor, crie a view e tente novamente.", 1);
        }
        require $viewFile;
    }

    /**
     * Define o diretório das views parciais.
     *
     * @param string $partialsDir Diretório
     */
    public function setPartialsDir(string $partialsDir, bool $overwrite = false): self
    {
        /** @var AbstractEnvironment $env */
        $env = $this->configs->getCurrentEnv();

        /** @var Views $viewConfig */
        $viewConfig = $this->configs->env->$env->views;
        $viewsDir = $viewConfig->getViewDirectory();

        $partialsDir = false === $overwrite ? $this->subfolder.$partialsDir : $partialsDir;

        $this->partialsDir = 'app'.DIRECTORY_SEPARATOR.$viewsDir.DIRECTORY_SEPARATOR.$partialsDir.DIRECTORY_SEPARATOR;

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
     * @param string $subfolder
     */
    public function setSubfolder(string $subfolder): self
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
}
