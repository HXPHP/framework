<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Configs\Modules\Auth;
use HXPHP\System\Configs\Modules\Database;
use HXPHP\System\Configs\Modules\Mail;
use HXPHP\System\Configs\Modules\Menu;
use HXPHP\System\Configs\Modules\Views;
use HXPHP\System\Tools;

/**
 * Class AbstractEnvironment.
 *
 * @property Auth $auth
 * @property Mail $mail
 * @property Menu $menu
 * @property Database $database
 * @property Views $views
 */
abstract class AbstractEnvironment
{
    public $baseURI;

    public function __construct()
    {
        //Configurações variáveis por ambiente
        $this->baseURI = '/';

        return $this->loadModules();
    }

    public function registerModule(string $type, string $module, string $name = null)
    {
        switch ($type) {
            case 'local':
                $module_class = Tools::filteredName(ucwords($module));

                if ($name) {
                    $module = $name;
                }

                if (!class_exists($module_class)) {
                    throw new \Exception("O modulo local <'$module_class'> informado nao existe.", 1);
                } else {
                    $this->$module = new $module_class();
                }

                break;
            case 'composer':
                $module_class = Tools::filteredName(ucwords($module));
                $object = $module_class.'\src\Config';

                if ($name) {
                    $module = $name;
                }

                if (!class_exists($object)) {
                    throw new \Exception("O modulo composer <'$object'> informado nao existe.", 1);
                } else {
                    $this->$module = new $object();
                }
                break;
        }

        return $this;
    }

    public function registerModules(array $modules)
    {
        foreach ($modules as $module => $info) {
            $type = $info['type'] ?? '';
            $name = $info['name'] ?? '';

            if (empty($type)) {
                continue;
            }

            $data = [$type, $module, $name];

            call_user_func_array([$this, 'registerModule'], $data);
        }
    }

    public function loadModules()
    {
        $modules = [
            'database',
            'mail',
            'menu',
            'auth',
            'views',
        ];

        foreach ($modules as $module) {
            $module_class = Tools::filteredName(ucwords($module));
            $object = 'HXPHP\System\Configs\Modules\\'.$module_class;

            if (!class_exists($object)) {
                throw new \Exception("O modulo <'$object'> informado nao existe.", 1);
            } else {
                $this->$module = new $object();
            }
        }

        return $this;
    }
}
