<?php

namespace HXPHP\System;

use HXPHP\System\Configs\Config;
use HXPHP\System\Http\Response;

class App
{
    /**
     * Injeção das configurações.
     *
     * @var object
     */
    public $configs;

    /**
     * Injeção do Router.
     *
     * @var object
     */
    public $router;

    /**
     * Injeção do Response.
     *
     * @var object
     */
    public $response;

    /**
     * Método Construtor.
     */
    public function __construct(Config $configs)
    {
        $this->configs = $configs;
        $this->router = new Router($configs->baseURI, $configs->global->controllers->directory);
        $this->response = new Response();
    }

    /**
     * Configuração do ORM.
     */
    public function ActiveRecord()
    {
        $cfg = \ActiveRecord\Config::instance();
        $cfg->set_model_directory($this->configs->models->directory);
        $cfg->set_connections(
                [
                    'development' => $this->configs->database->driver.'://'
                    .$this->configs->database->user
                    .':'.$this->configs->database->password
                    .'@'.$this->configs->database->host
                    .'/'.$this->configs->database->dbname
                    .'?charset='.$this->configs->database->charset,
                ]
        );

        return $cfg;
    }

    /**
     * Executa a aplicação.
     */
    public function run()
    {
        /**
         * Variáveis.
         */
        $subfolder = 'default' === $this->router->subfolder ? '' :
                $this->router->subfolder.DIRECTORY_SEPARATOR;
        $controller = $this->router->controller;
        $action = $this->router->action;

        $controllersDir = $this->configs->controllers->directory;
        $notFoundController = $this->configs->controllers->notFound;

        /**
         * Caminho do controller.
         *
         * @var string
         */
        $controller_directory = $controllersDir.$subfolder;
        $controllerFile = $controller_directory.$controller.'.php';
        $notFoundControllerFile = $controller_directory.$notFoundController.'.php';

        if (!file_exists($controllerFile)) {
            $controllerFile = $notFoundControllerFile;
        }

        //Inclusão do Controller
        require_once $controllerFile;

        //Verifica se a classe correspondente ao Controller existe
        if (!class_exists($controller)) {
            require_once $notFoundControllerFile;

            $controller = $notFoundController;
        }

        $app = new $controller($this->configs);

        //Verifica se a Action requisitada não existe
        if (!method_exists($app, $action)) {
            $action = 'indexAction';
        }

        //Injeção das configurações
        $app->setConfigs($this->configs);
        $app->view->setConfigs($this->configs, $subfolder, $controller, $action);

        /*
         * Atribuição de parâmetros
         */
        call_user_func_array([&$app, $action], $this->router->params);

        /*
         * Renderização da VIEW
         */
        $app->view->flush();
    }
}
