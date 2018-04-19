<?php

namespace HXPHP\System;

use HXPHP\System\Configs\Config;

class Loader
{
    /**
     * @var array
     */
    public static $loaded = [];

    /**
     * @var self
     */
    private static $instance = null;

    const CORE_NAMESPACE = 'HXPHP\System\\',
        APP_NAMESPACE = '\App\\';

    /**
     * @param string $class
     * @param string $path
     * @param string $app
     *
     * @version 1.0
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

        $loadedByController = null;

        if(is_bool($args[0])){
            $loadedByController = true;
            unset($args[0]);
            $args = array_values($args);
        }

        $type = $args[0];
        if(!in_array($type, ['core','hxphp', 'composer', 'local'])){
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
        if($type === 'core')
        {
            $object = self::CORE_NAMESPACE . $object;
            if(!$moduleName)
                $moduleName = end($explode);
        }
        if($type === 'hxphp')
        {
            $object = self::CORE_NAMESPACE . $object;
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

            /** @var Config $configs */
            $configs = self::getLoaded('Config');

            $enviroment = $configs->define->getDefault();

            if ($params)
            {
                $ref = new \ReflectionClass($object);

                self::$loaded[$name] = [
                    'name' => $moduleName,
                    'object' => $ref->newInstanceArgs($params)
                ];
            } else {
                if(isset($configs->env->$enviroment->$moduleName))
                {
                    self::$loaded[$name] = [
                        'name' => $moduleName,
                        'object' => new $object($configs->env->$enviroment->$moduleName)
                    ];
                } else {
                    self::$loaded[$name] = [
                        'name' => $moduleName,
                        'object' => new $object()
                    ];
                }
            }

            if($loadedByController)
            {
                return self::$loaded[$name];
            }

            return self::$loaded[$name]['object'];
        }

        return null;
    }

    public static function loadStatic()
    {
        $loader = Loader::getLoadedStatic('Loader');
        if(empty($loader)){
            $loader = self::getInstance();
            return call_user_func_array([$loader,'load'],func_get_args());
        }
        return call_user_func_array([$loader,'load'],func_get_args());
    }

    /**
     * @param $class
     * @param array $object
     *
     * @version 1.0
     */
    public function addLoadedInstance($class, array $object)
    {
        $name = strtolower($object['name'] ?? $class);

        self::$loaded[$name]['name'] = $class;
        self::$loaded[$name]['object'] = $object['object'];
    }

    /**
     * @param $class
     * @param array $object
     *
     * @version 1.0
     */
    public static function addLoadedInstanceStatic($class, array $object)
    {
        $name = strtolower($object['name'] ?? $class);

        self::$loaded[$name]['name'] = $class;
        self::$loaded[$name]['object'] = $object['object'];
    }

    /**
     * @return Loader
     *
     * @version 1.0
     */
    public static function getInstance()
    {
        if(self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * @param string $class
     * @return array
     *
     * @version 1.0
     */
    public function getLoaded(string $class = '', $details = false)
    {
        if(!empty($class)){

            $class = strtolower($class);

            if(isset(self::$loaded[$class])){
                if($details){
                    return self::$loaded[$class];
                }

                return self::$loaded[$class]['object'];
            } else
                return null;

        }
        else
            return self::$loaded;
    }

    /**
     * @param string $class
     * @return array
     *
     * @version 1.0
     */
    public static function getLoadedStatic(string $class = '', $details = false)
    {
        if(!empty($class)){

            $class = strtolower($class);

            if(isset(self::$loaded[$class])){
                if($details){
                    return self::$loaded[$class];
                }

                return self::$loaded[$class]['object'];
            }
            else
                return [];
        } else
            return self::$loaded;
    }

}