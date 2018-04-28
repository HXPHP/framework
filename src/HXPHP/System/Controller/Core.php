<?php

namespace HXPHP\System\Controller;

use HXPHP\System\Configs\Config;
use HXPHP\System\Http\Request;
use HXPHP\System\Http\Response;
use HXPHP\System\Loader;
use HXPHP\System\View\Core as ViewCore;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;

/**
 * Class Core.
 *
 * @property \HXPHP\System\View\Core $view
 * @property Response $response
 */
class Core
{
    /**
     * Injeção das Configurações.
     *
     * @var Config
     */
    public $configs = null;

    /**
     * Injeção do Http Request.
     *
     * @var object
     */
    public $request;

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
        $this->view = new ViewCore($configs);
        $this->response = new Response;

        $this->setConfigs($configs);
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
        $loader = Loader::getLoadedStatic('Loader');
        $classLoaded = call_user_func_array([$loader, 'load'], array_merge([true], func_get_args()));

        $name = strtolower($classLoaded['name']);
        $this->$name = $classLoaded['object'];

        return $classLoaded['object'];
    }

    public function getLoaderClass(string $load)
    {
        return Loader::getLoadedStatic($load);
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
        $path = true === $controller ? $this->view->template->getPath().DIRECTORY_SEPARATOR : $this->view->template->getSubfolder();

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

        $this->response->redirectTo($URL);
    }
}
