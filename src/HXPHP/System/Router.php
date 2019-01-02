<?php

namespace HXPHP\System;

class Router
{
    public $subfolder = '';

    public $controller = 'IndexController';

    private $baseURI = null;
    private $controllerDirectory = null;

    private $explode = [];

    public $action = 'indexAction';

    public $params = [];

    public function __construct(string $baseURI = '', string $controllerDirectory = '')
    {
        $this->subfolder = 'default';
        $this->baseURI = $baseURI;
        $this->controllerDirectory = $controllerDirectory;

        return $this->initialize();
    }

    public function initialize()
    {
        if (!$this->baseURI || !$this->controllerDirectory ||
            false === array_key_exists('REQUEST_URI', $_SERVER)) {
            return $this;
        }

        $this->explode = $this->getExplode();

        $baseURICount = count(array_filter(explode('/', $this->baseURI)));

        if (count($this->explode) == $baseURICount) {
            return $this;
        }

        $this->explode = $this->handleExplode($baseURICount);

        $this->handle();

        unset($this->explode[0], $this->explode[1]);

        $this->params = array_values($this->explode);
    }

    private function getExplode()
    {
        $rawExplode = explode('/', $_SERVER['REQUEST_URI']);
        $filteredExplode = array_filter($rawExplode);

        return array_values($filteredExplode);
    }

    private function handleExplode(int $baseURICount): array
    {
        if (count($this->explode) != $baseURICount) {
            for ($i = 0; $i < $baseURICount; $i++) {
                unset($this->explode[$i]);
            }

            $this->explode = array_values($this->explode);
        }

        return $this->explode;
    }

    private function handle()
    {
        if (file_exists($this->controllerDirectory.$this->explode[0])) {
            $this->handleCompleteRoute();
        } elseif (1 == count($this->explode)) {
            $this->controller = Tools::filteredName($this->explode[0]).'Controller';

            return $this;
        } else {
            $this->controller = Tools::filteredName($this->explode[0]).'Controller';
            $this->action = lcfirst(Tools::filteredName($this->explode[1])).'Action';
        }
    }

    private function handleCompleteRoute()
    {
        $this->subfolder = $this->explode[0];

        if (isset($this->explode[1])) {
            $this->controller = Tools::filteredName($this->explode[1]).'Controller';
        }

        if (isset($this->explode[2])) {
            $this->action = lcfirst(Tools::filteredName($this->explode[2])).'Action';

            unset($this->explode[2]);
        }
    }
}
