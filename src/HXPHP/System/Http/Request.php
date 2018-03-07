<?php

namespace HXPHP\System\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;

class Request extends SymfonyHttpFoundationRequest
{
    /**
     * Filtros customizados de tratamento.
     *
     * @var array
     */
    public $custom_filters = [];

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Define filtros/flags customizados (http://php.net/manual/en/filter.filters.sanitize.php).
     *
     * @param array $custom_filters Array com nome do campo e seu respectivo filtro
     */
    public function setCustomFilters(array $custom_filters = []): array
    {
        return $this->custom_filters = $custom_filters;
    }

    /**
     * Realiza o tratamento das super globais.
     *
     * @param array|string $data Dados que serão filtrados
     *
     * @return array|string Constate tratada
     */
    public function filter($data, $key = null)
    {
        $filters = $this->custom_filters;

        if (!is_null($key)) {
            if (array_key_exists($key, $filters)) {
                $filter = $filters[$key];

                if (is_array($filter)) {
                    $only_filter = $filter['filter'];
                    $flags = array_key_exists('flags', $filter) ? $filter['flags'] : [];

                    return filter_var($data, $only_filter, $flags);
                }

                return filter_var($data, $filter);
            }

            return !is_null($data) ? filter_var($data, FILTER_SANITIZE_STRING) : $data;
        }

        if (is_array($data) && !$key) {
            foreach ($data as $key => $value) {
                if (!array_key_exists($key, $filters)) {
                    $filters[$key] = constant('FILTER_SANITIZE_STRING');
                }
            }

            return filter_var_array($data, $filters);
        }

        return $data;
    }

    /**
     * Obtém os dados enviados através do método GET.
     *
     * @param string $name Nome do parâmetro
     */
    public function get($name = null, $default = null)
    {
        if ($name && !is_string($name)) {
            return false;
        }

        if (!$name) {
            $data = $this->query->all();
        } else {
            $data = $this->query->get($name, $default);
        }

        return $this->filter($data, $name);
    }

    /**
     * Obtém os dados enviados através do método POST.
     *
     * @param string $name Nome do parâmetro
     */
    public function post(string $name = null)
    {
        if ($name && !is_string($name)) {
            return false;
        }

        if (!$name) {
            $data = $this->request->all();
        } else {
            $data = $this->request->get($name);
        }

        return $this->filter($data, $name);
    }

    /**
     * Obtém os dados da superglobal $_SERVER.
     *
     * @param string $name Nome do parâmetro
     */
    public function server(string $name = null)
    {
        if ($name && !is_string($name)) {
            return false;
        }

        if (!$name) {
            return $this->server->all();
        }

        return $this->server->get($name);
    }

    /**
     * Obtém os dados da superglobal $_COOKIE.
     *
     * @param string $name Nome do parâmetro
     */
    public function cookie(string $name = null)
    {
        if ($name && !is_string($name)) {
            return false;
        }

        if (!$name) {
            $data = $this->cookies->all();
        } else {
            $data = $this->cookies->get($name);
        }

        return $this->filter($data, $name);
    }

    /**
     * Verifica se o método da requisição é GET.
     *
     * @return bool Status da verificação
     */
    public function isGet(): bool
    {
        return $this->getMethod('GET');
    }

    /**
     * Verifica se o método da requisição é POST.
     *
     * @return bool Status da verificação
     */
    public function isPost(): bool
    {
        return $this->getMethod('POST');
    }

    /**
     * Verifica se o método da requisição é PUT.
     *
     * @return bool Status da verificação
     */
    public function isPut(): bool
    {
        return $this->getMethod('PUT');
    }

    /**
     * Verifica se o método da requisição é DELETE.
     *
     * @return bool Status da verificação
     */
    public function isDelete(): bool
    {
        return $this->getMethod('DELETE');
    }

    /**
     * Verifica se o método da requisição é HEAD.
     *
     * @return bool Status da verificação
     */
    public function isHead(): bool
    {
        return $this->getMethod('HEAD');
    }

    /**
     * Verifica se os inputs no método requisitado estão no formato correto conforme o array informado $custom_filters.
     *
     * @return bool Inputs estão corretos ou não
     */
    public function isValid(): bool
    {
        $method = $this->getMethod();

        return false === array_search(false, $this->$method(), true) ? true : false;
    }
}
