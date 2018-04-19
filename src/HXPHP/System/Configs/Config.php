<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Configs\Modules\Auth;
use HXPHP\System\Configs\Modules\Database;
use HXPHP\System\Configs\Modules\Mail;
use HXPHP\System\Configs\Modules\Views;

/**
 * Class Config
 * @package HXPHP\System\Configs
 *
 * @property GlobalConfig $global
 * @property Environment $env
 * @property DefineEnvironment $define
 *
 * @property Auth $auth
 * @property Database $database
 * @property Mail $mail
 * @property Views $views
 *
 * @property string $title
 * @property object $site
 * @property object $controllers
 * @property object $models
 */
class Config extends Bootstrap
{
    public $global;

    public $env;

    public $define;

    public function __construct()
    {
        parent::__construct();

        $this->global = new GlobalConfig();
        $this->define = new DefineEnvironment();

        $this->env = new Environment($this->define);
        $this->env->add();
    }

    public function __get(string $param)
    {
        $current = $this->define->getDefault();

        if (isset($this->env->$current->$param)) {
            return $this->env->$current->$param;
        } elseif (isset($this->global->$param)) {
            return $this->global->$param;
        }

        throw new \Exception("Parametro/Modulo '$param' nao encontrado. Verifique se o ambiente definido esta configurado e os modulo utilizados registrados.", 1);
    }

    public function getCurrentEnv()
    {
        return $this->define->getDefault();
    }
}
