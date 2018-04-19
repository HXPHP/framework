<?php

namespace HXPHP\System;

class Router
{
    public $subfolder = '';

    public $controller = 'IndexController';

    public $action = 'indexAction';

    public $params = [];

    public function __construct(string $baseURI = '', string $controller_directory = '')
    {
        $this->subfolder = 'default';
        $this->initialize($baseURI, $controller_directory);
    }

    public function initialize(string $baseURI, string $controller_directory)
    {
        if (!empty($baseURI)
            && !empty($controller_directory) && array_key_exists('REQUEST_URI', $_SERVER)) {
            $explode = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));

            $baseURICount = count(array_filter(explode('/', $baseURI)));

            if (count($explode) == $baseURICount) {
                return $this;
            }

            if (count($explode) != $baseURICount) {
                for ($i = 0; $i < $baseURICount; $i++) {
                    unset($explode[$i]);
                }

                $explode = array_values($explode);
            }

            if (file_exists($controller_directory.$explode[0])) {
                $this->subfolder = $explode[0];

                if (isset($explode[1])) {
                    $this->controller = Tools::filteredName($explode[1]).'Controller';
                }

                if (isset($explode[2])) {
                    $this->action = lcfirst(Tools::filteredName($explode[2])).'Action';

                    unset($explode[2]);
                }
            } elseif (1 == count($explode)) {
                $this->controller = Tools::filteredName($explode[0]).'Controller';

                return $this;
            } else {
                $this->controller = Tools::filteredName($explode[0]).'Controller';
                $this->action = lcfirst(Tools::filteredName($explode[1])).'Action';
            }

            unset($explode[0], $explode[1]);

            $this->params = array_values($explode);
        }
    }
}
