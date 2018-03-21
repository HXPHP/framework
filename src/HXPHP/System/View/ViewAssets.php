<?php

namespace HXPHP\System\View;
use HXPHP\System\Configs\AbstractEnvironment;
use HXPHP\System\Configs\Config;

/**
 * Class ViewAssets
 * @package HXPHP\System\View
 */
class ViewAssets
{
    protected $assets = [
        'css' => [],
        'js'  => [],
    ];

    protected $configs;

    public function __construct(Config $config)
    {
        $this->configs = $config;

        /** @var AbstractEnvironment $env */
        $env = $config->getCurrentEnv();

        $this->globalAssets($config->env->$env->views->getAssets());
    }

    /**
     * Define os arquivos customizáveis que serão utilizados.
     *
     * @param string       $type   Tipo do arquivo
     * @param string|array $assets Arquivo Único | Array com os arquivos
     */
    public function setAssets(string $type, $assets): self
    {
        (is_array($assets)) ?
            $this->assets[$type] = array_merge($this->assets[$type], $assets) :
            array_push($this->assets[$type], $assets);

        return $this;
    }

    /**
     * @return array
     */
    public function getAssets(string $type = ''): array
    {
        if(in_array($type,['js','css'])){
            return $this->assets[$type];
        }

        return $this->assets;
    }

    /**
     * Inclui os arquivos customizados.
     *
     * @param string $type          Tipo de arquivo incluso, como: css ou js
     * @param array  $custom_assets Links dos arquivos que serão incluídos
     *
     * @return string HTML formatado de acordo com o tipo de arquivo
     */
    public function assets(string $type, array $custom_assets = []): string
    {
        $add_assets = '';

        $tag = '';

        switch ($type) {
            case 'css':
                $tag = '<link type="text/css" rel="stylesheet" href="%s">'."\n\r";
                break;
            case 'js':
                $tag = '<script type="text/javascript" src="%s"></script>'."\n\r";

                break;
        }

        if (count($custom_assets)) {
            foreach ($custom_assets as $file) {
                $add_assets .= sprintf($tag, $file);
            }
        }

        return $add_assets;
    }

    /**
     * @param array $assets
     */
    private function globalAssets(array $assets)
    {
        $this->assets = array_merge($this->assets, $assets);
    }

}