<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Configs\Environments\Development;
use HXPHP\System\Configs\Environments\Production;
use HXPHP\System\Configs\Environments\Tests;
use HXPHP\System\Tools;

/**
 * Class Environment.
 *
 * @property Development $development
 * @property Tests $tests
 * @property Production $production
 */
class Environment
{
    public $defaultEnvironment;

    private $defineEnvironment;

    public function __construct(DefineEnvironment $env)
    {
        $this->defineEnvironment = $env;
        $this->add();
    }

    public function add(string $environment = null)
    {
        if (!$environment) {
            $environment = $this->defaultEnvironment = $this->defineEnvironment->getDefault();
        }

        $name = strtolower(Tools::filteredName($environment));
        $object = 'HXPHP\System\Configs\Environments\\'.ucfirst(Tools::filteredName($environment));

        if (!class_exists($object)) {
            throw new \Exception('O ambiente informado nao esta definido nas configuracoes do sistema.');
        } else {
            $this->$name = new $object();

            return $this->$name;
        }
    }
}
