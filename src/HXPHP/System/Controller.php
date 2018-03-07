<?php

namespace HXPHP\System;

use HXPHP\System\Configs\Config;
use HXPHP\System\Controller\Core;

class Controller extends Core
{
    public function __construct(Config $configs = null)
    {
        parent::__construct($configs);
    }

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
        $type = $args[0];
        if (!in_array($type, ['hxphp', 'composer', 'local'])) {
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
        if (isset($args[0]['name'])) {
            $moduleName = $args[0]['name'];
            unset($args[0]);
            $args = array_values($args);
        }
        /**
         * Tratamento que adiciona a pasta do módulo.
         */
        $explode = explode('\\', $object);
        if ($type === 'hxphp') {
            $object = 'HXPHP\System\\'.$object;
            $object = $object.'\\'.end($explode);
            if (!$moduleName) {
                $moduleName = end($explode);
            }
        }
        if ($type === 'composer') {
            if (count($explode) == 1) {
                $module = $explode[0];
                $object = $module.'\src\\'.$module;
                if (!$moduleName) {
                    $moduleName = $module;
                }
            } else {
                $object = implode('\\', $explode);
                if (!$moduleName) {
                    $moduleName = $explode[0];
                }
            }
        }
        if ($type === 'local') {
            if (count($explode) == 1) {
                $module = $explode[0];
                $object = $module.'\src\\'.$module;
                if (!$moduleName) {
                    $moduleName = $module;
                }
            } else {
                $object = implode('\\', $explode);
                if (!$moduleName) {
                    $moduleName = $explode[0];
                }
            }
        }
        /**
         * Define os demais argumentos passados como
         * parâmetros para o construtor do objeto injetado.
         */
        $params = !($args) ? [] : array_values($args);
        if (class_exists($object)) {
            $name = end($explode);
            $name = strtolower(Tools::filteredName($name));
            $enviroment = $this->configs->define->getDefault();

            if ($params) {
                var_dump($params);
                $ref = new \ReflectionClass($object);
                $this->view->$name = $ref->newInstanceArgs($params);
            } else {
                if (isset($this->configs->env->$enviroment->$moduleName)) {
                    $this->view->$name = new $object($this->configs->env->$enviroment->$moduleName);
                } else {
                    $this->view->$name = new $object();
                }
            }

            return $this->view->$name;
        }
    }
}
