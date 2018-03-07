<?php

namespace HXPHP\System\Controller;

use HXPHP\System\Configs\Config;
use HXPHP\System\Http\Request;
use HXPHP\System\Http\Response;
use HXPHP\System\Tools;
use HXPHP\System\View;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;

class Core
{
    /**
     * Injeção das Configurações.
     *
     * @var object
     */
    public $configs = null;

    /**
     * Injeção do Http Request.
     *
     * @var object
     */
    private $request;

    /**
     * Injeção do Http Response.
     *
     * @var object
     */
    private $response;

    /**
     * Injeção da View.
     *
     * @var object
     */
    public $view;

    public function __construct(Config $configs = null)
    {
        //Injeção da VIEW
        $this->view = new View();
        $this->response = new Response();

        if ($configs) {
            $this->setConfigs($configs);
        }
    }

    /**
     * Injeta as configurações.
     *
     * @param Config $configs Objeto com as configurações da aplicação
     *
     * @return object
     */
    public function setConfigs(Config $configs): self
    {
        //Injeção das dependências
        $this->configs = $configs;

        SymfonyHttpFoundationRequest::setFactory(function (
            array $query = [],
            array $request = [],
            array $attributes = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
            $content = null
        ) {
            return new Request(
                $query,
                $request,
                $attributes,
                $cookies,
                $files,
                $server,
                $content
            );
        });

        $this->request = Request::createFromGlobals();

        return $this;
    }

    /**
     * Default Action.
     */
    public function indexAction()
    {
    }

    /**
     * Carrega serviços, módulos e helpers.
     *
     * @param string       $object Nome da classe
     * @param string|array $params Parâmetros do método construtor
     *
     * @return object Objeto injetado
     */
    public function load()
    {
        $total_args = func_num_args();

        if (!$total_args) {
            throw new \Exception('Nenhum objeto foi definido para ser carregado.', 1);
        }
        /**
         * Retorna todos os argumentos e define o primeiro como
         * o objeto que será injetado.
         *
         * @var array
         */
        $args = func_get_args();
        $object = $args[0];

        /*
         * Define os demais argumentos passados como
         * parâmetros para o construtor do objeto injetado
         */
        unset($args[0]);
        $params = !($args) ? [] : array_values($args);

        /**
         * Tratamento que adiciona a pasta do módulo.
         */
        $explode = explode('\\', $object);
        $object = $object.'\\'.end($explode);
        $object = 'HXPHP\System\\'.$object;

        if (class_exists($object)) {
            $name = end($explode);
            $name = strtolower(Tools::filteredName($name));

            if ($params) {
                $ref = new \ReflectionClass($object);
                $this->view->$name = $ref->newInstanceArgs($params);
            } else {
                $this->view->$name = new $object();
            }

            return $this->view->$name;
        }
    }

    /**
     * Método mágico para atalho de objetos injetados na VIEW.
     *
     * @param string $param Atributo
     *
     * @return mixed Conteúdo do atributo ou Exception
     */
    public function __get(string $param)
    {
        if (isset($this->view->$param)) {
            return $this->view->$param;
        } elseif (isset($this->$param)) {
            return $this->$param;
        } else {
            throw new \Exception("Parametro <$param> nao encontrado.", 1);
        }
    }

    /**
     * Método que retorna o caminho relativo.
     *
     * @param string $URL        Geralmente a action e parâmetros
     * @param bool   $controller Define se o controller também será retornado
     *
     * @return string Link relativo
     */
    public function getRelativeURL(string $URL, bool $controller = true): string
    {
        $path = true === $controller ? $this->view->path.DIRECTORY_SEPARATOR : $this->view->subfolder;

        return $this->configs->baseURI.$path.$URL;
    }

    /**
     * Redirecionamento.
     *
     * @param string $URL        Link de redirecionamento
     * @param bool   $external   Define se o redirecionamento será relativo ou absoluto
     * @param bool   $controller Define se o controller também será retornado
     */
    public function redirectTo(string $URL, bool $external = true, bool $controller = true)
    {
        $URL = false === $external ? $this->getRelativeURL($URL, $controller) : $URL;

        return $this->response->redirectTo($URL);
    }
}
