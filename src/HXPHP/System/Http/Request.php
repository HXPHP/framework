<?php
namespace HXPHP\System\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;

class Request extends SymfonyHttpFoundationRequest
{
    /**
     * Filtros customizados de tratamento
     * @var array
     */
    public $custom_filters = [];

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Define filtros/flags customizados (http://php.net/manual/en/filter.filters.sanitize.php)
     * @param array $custom_filters Array com nome do campo e seu respectivo filtro
     */
    public function setCustomFilters(array $custom_filters = []): array
    {
        return $this->custom_filters = $custom_filters;
    }

    /**
     * Realiza o tratamento das super globais
     * @param  array $request 		  Array nativo com campos e valores passados
     * @param  const $data    		  Constante que será tratada
     * @param  array $custom_filters  Filtros customizados para determinados campos
     * @return array                  Constate tratada
     */
    public function filter(array $request, $data, array $custom_filters = [])
    {
        $filters = [];

        foreach ($request as $key => $value)
            if (!array_key_exists($key, $custom_filters))
                $filters[$key] = constant('FILTER_SANITIZE_STRING');

        if (is_array($custom_filters) && is_array($custom_filters))
            $filters = array_merge($filters, $custom_filters);

        return filter_input_array($data, $filters);
    }

    /**
     * Obtém os dados da superglobal $_SERVER
     * @param  string $name Nome do parâmetro
     * @return null         Retorna o array $_SERVER geral ou em um índice específico
     */
    public function server(string $name = null)
    {
        $server = $this->filter($_SERVER, INPUT_SERVER, $this->custom_filters);

        if (!$name)
            return $server;

        if (!isset($server[$name]) && !isset($_SERVER[$name]))
            return null;

        if (is_null($server[$name]))
            return $_SERVER[$name];

        if (!isset($server[$name]))
            return null;

        return $server[$name];
    }

    /**
     * Obtém os dados da superglobal $_COOKIE
     * @param string $name Nome do parâmetro
     * @return null Retorna o array $_COOKIE geral ou em um índice específico
     */
    public function cookie(string $name = null)
    {
        $cookie = $this->filter($_COOKIE, INPUT_COOKIE, $this->custom_filters);

        if (!$name)
            return $cookie;

        if (!isset($cookie[$name]))
            return null;

        return $cookie[$name];
    }

    /**
     * Retorna o método da requisição
     * @param  string $value Nome do método
     * @return null          Retorna um booleano ou o método em si
     */
    public function getMethod(string $value = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($value)
            return $method == $value;

        return $method;
    }

    /**
     * Verifica se o método da requisição é POST
     * @return boolean Status da verificação
     */
    public function isPost(): bool
    {
        return $this->getMethod('POST');
    }

    /**
     * Verifica se o método da requisição é GET
     * @return boolean Status da verificação
     */
    public function isGet(): bool
    {
        return $this->getMethod('GET');
    }

    /**
     * Verifica se o método da requisição é PUT
     * @return boolean Status da verificação
     */
    public function isPut(): bool
    {
        return $this->getMethod('PUT');
    }

    /**
     * Verifica se o método da requisição é HEAD
     * @return boolean Status da verificação
     */
    public function isHead(): bool
    {
        return $this->getMethod('HEAD');
    }

    /**
     * Verifica se os inputs no método requisitado estão no formato correto conforme o array informado $custom_filters
     *
     * @return boolean Inputs estão corretos ou não
     */
    public function isValid(): bool
    {
        $method = $this->getMethod();

        return array_search(false, $this->$method(), true) === false ? true : false;
    }
}