<?php

namespace HXPHP\System\View;

use HXPHP\System\Configs\AbstractEnvironment;
use HXPHP\System\Configs\Config;
use HXPHP\System\Configs\Modules\Views;

class Core
{
    use ViewMethods;

    /**
     * Título da página.
     *
     * @var string
     */
    public $title = '';

    /**
     * Injeção das Configurações.
     *
     * @var object
     */
    private $configs;

    /**
     * @var ViewTemplate
     */
    public $template;

    /**
     * @var ViewAssets
     */
    private $assets;

    /**
     * @var ViewPartial
     */
    private $partial;

    public function __construct(Config $configs)
    {
        $this->configs = $configs;

        $this->template = new ViewTemplate($configs);
        $this->assets   = new ViewAssets($configs);
        $this->partial  = new ViewPartial($configs);
    }

    public function setConfigs(Config $configs, string $subfolder, string $controller, string $action)
    {
        /*
         * Injeção das Configurações
         * @var object
         */
        $this->configs = $configs;

        /** @var AbstractEnvironment $env */
        $env = $configs->getCurrentEnv();

        /** @var Views $viewConfig */
        $viewConfig = $configs->env->$env->views;

        /*
         * Subfolder
         * @var mixed
         */
        $this->subfolder = $subfolder;

        /**
         * Tratamento das variáveis.
         */
        $controller = strtolower(str_replace('Controller', '', $controller));
        $action = ($controller == $configs->controllers->notFound ? 'indexAction' : $action);
        $action = str_replace('Action', '', $action);

        $this->partial->setPartialsDir($viewConfig->getPartialsDir());

        $this->template->setSubfolder($subfolder)
            ->setTemplate($viewConfig->isTemplate())
            ->setTemplateParts($viewConfig->getTemplateTree())
            ->setTemplateTreeOrder($viewConfig->getTemplateTreeOrder())
            ->setPath($controller)
            ->setFile($action)
            ->setTitle($viewConfig->getTitle());
    }

    /**
     * Renderiza a VIEW.
     *
     * @param string $view Nome do arquivo, sem extensão, a ser utilizado como VIEW
     */
    public function flush()
    {

        /** @var AbstractEnvironment $env */
        $env = $this->configs->getCurrentEnv();

        /** @var Views $viewConfig */
        $viewConfig = $this->configs->env->$env->views;

        $template   = $this->template;
        $assets     = $this->assets;

        $default_data = [
            'title' => $template->getTitle(),
        ];

        //Inclusão de ASSETS
        $add_css = $assets->assets('css', $assets->getAssets('css'));
        $add_js = $assets->assets('js', $assets->getAssets('js'));

        $data = array_merge($default_data, ['js' => $add_js, 'css' => $add_css]);

        $template->setVars($data);

        //Variáveis
        $baseURI = $this->configs->baseURI;

        //Atribuição das constantes
        define('BASE', $baseURI);
        define('ASSETS', $baseURI.'public/assets/');
        define('BOWER', $baseURI.'public/bower_components/');
        define('IMG', $baseURI.'public/img/');
        define('CSS', $baseURI.'public/css/');
        define('JS', $baseURI.'public/js/');

        $loadTemplate = new LoadTemplate($template, $viewConfig, $this->partial);
        $loadTemplate->load();
    }

}