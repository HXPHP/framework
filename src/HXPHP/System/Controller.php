<?php
namespace HXPHP\System;

use HXPHP\System\{
    Http,
    Configs\Config
};

class Controller
{
    /**
     * Injeção das Configurações
     * @var object
     */
    public $configs = null;

    /**
     * Injeção do Http Request
     * @var object
     */
    private $request;

    /**
     * Injeção do Http Response
     * @var object
     */
    private $response;

    /**
     * Injeção da View
     * @var object
     */
    public $view;

    public function __construct(Config $configs = null)
    {
        //Injeção da VIEW
        $this->view = new View;
        $this->response = new Http\Response;

        if ($configs)
            $this->setConfigs($configs);
    }

    /**
     * Injeta as configurações
     * @param  Config $configs Objeto com as configurações da aplicação
     * @return object
     */
    public function setConfigs(Config $configs): self
    {
        //Injeção das dependências
        $this->configs = $configs;
        $this->request = new Http\Request($configs->baseURI, $configs->controllers->directory);

        return $this;
    }

    /**
     * Default Action
     */
    public function indexAction() { }

    /**
     * Carrega serviços, módulos e helpers
     * @param  string $object Nome da classe
     * @param  string|array  $params Parâmetros do método construtor
     * @return object         Objeto injetado
     */
    public function load()
    {
        $total_args = func_num_args();

        if (!$total_args)
            throw new \Exception("Nenhum objeto foi definido para ser carregado.", 1);

        /**
         * Retorna todos os argumentos e define o primeiro como
         * o objeto que será injetado
         * @var array
         */
        $args = func_get_args();
        $type = $args[0];

        if(!in_array($type, ['hxphp', 'composer', 'local'])){
            $type = 'hxphp';

            $object = $args[0];

            unset($args[0]);
        } else {
            $object = $args[1];

            unset($args[0]);
            unset($args[1]);
        }

        $args = array_values($args);

        $moduleName = null;

        if(isset($args[0]['name'])){
            $moduleName = $args[0]['name'];
            unset($args[0]);

            $args = array_values($args);
        }

        /**
         * Tratamento que adiciona a pasta do módulo
         */
        $explode = explode('\\', $object);

        if($type === 'hxphp')
        {
            $object = 'HXPHP\System\\' . $object;
            $object = $object . '\\' . end($explode);

            if(!$moduleName)
                $moduleName = end($explode);
        }

        if($type === 'composer')
        {
            if(count($explode) == 1)
            {
                $module = $explode[0];
                $object = $module.'\src\\'.$module;

                if(!$moduleName)
                    $moduleName = $module;

            } else {
                $object = implode('\\',$explode);

                if(!$moduleName)
                    $moduleName = $explode[0];
            }
        }

        if($type === 'local')
        {
            if(count($explode) == 1)
            {
                $module = $explode[0];
                $object = $module.'\src\\'.$module;

                if(!$moduleName)
                    $moduleName = $module;

            } else {
                $object = implode('\\',$explode);

                if(!$moduleName)
                    $moduleName = $explode[0];
            }
        }

        /**
         * Define os demais argumentos passados como
         * parâmetros para o construtor do objeto injetado
         */
        $params = !($args) ? [] : array_values($args);

        if (class_exists($object))
        {
            $name = end($explode);
            $name = strtolower(Tools::filteredName($name));

            $enviroment = $this->configs->define->getDefault();
            
            if ($params)
            {
                var_dump($params);
                $ref = new \ReflectionClass($object);
                $this->view->$name = $ref->newInstanceArgs($params);
            } else {

                if(isset($this->configs->env->$enviroment->$moduleName))
                {
                    $this->view->$name = new $object($this->configs->env->$enviroment->$moduleName);
                } else {
                    $this->view->$name = new $object();
                }
            }

            return $this->view->$name;
        }

        return null;
    }

    /**
     * Método mágico para atalho de objetos injetados na VIEW
     * @param  string $param Atributo
     * @return mixed         Conteúdo do atributo ou Exception
     */
    public function __get(string $param)
    {
        if (isset($this->view->$param))
            return $this->view->$param;

        elseif (isset($this->$param))
            return $this->$param;
        else
            throw new \Exception("Parametro <$param> nao encontrado.", 1);
    }

    /**
     * Método que retorna o caminho relativo
     * @param  string  $URL        Geralmente a action e parâmetros
     * @param  boolean $controller Define se o controller também será retornado
     * @return string              Link relativo
     */
    public function getRelativeURL(string $URL, bool $controller = true): string
    {
        $path = $controller === true ? $this->view->path . DIRECTORY_SEPARATOR : $this->view->subfolder;

        return $this->configs->baseURI . $path . $URL;
    }

    /**
     * Redirecionamento
     * @param  string $URL Link de redirecionamento
     * @param  boolean $external Define se o redirecionamento será relativo ou absoluto
     * @param  boolean $controller Define se o controller também será retornado
     */
    public function redirectTo(string $URL, bool $external = true, bool $controller = true)
    {
        $URL = $external === false ? $this->getRelativeURL($URL, $controller) : $URL;
        return $this->response->redirectTo($URL);
    }
}