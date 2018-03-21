<?php

namespace HXPHP\System\View;

use HXPHP\System\Configs\Modules\Views;

/**
 * Class LoadTemplate
 * @package HXPHP\System\View
 */
class LoadTemplate
{
    static $DS = DIRECTORY_SEPARATOR;

    /**
     * @var string
     */
    protected $subfolder = '';

    /**
     * @var array
     */
    protected $tree = [];

    /** @var  ViewTemplate */
    protected $template;

    /**
     * @var Views
     */
    protected $viewConfig;

    /**
     * @var array
     */
    protected $view_vars = [];

    public function __construct(ViewTemplate $template, Views $config, ViewPartial $partial)
    {
        $this->template = $template;

        $this->viewConfig = $config;

        $this->partial  = $partial;

        $this->treeOrderOrganize($template->getTemplateTreeOrder(),$template->getTemplateTree());
    }

    protected function treeOrderOrganize(array $order,array $tree)
    {
        if((!isset($tree['header'])) || (isset($tree['header']) && empty($tree['header'])))
        {
            $tree['header'] = 'header';
        }

        if((!isset($tree['view'])) || (isset($tree['view']) && empty($tree['view'])))
        {
            $tree['view'] = 'view';
        }

        if((!isset($tree['footer'])) || (isset($tree['footer']) && empty($tree['footer'])))
        {
            $tree['footer'] = 'footer';
        }

        foreach ($order as $key => $item){

            if(!is_string($key)){
                continue;
            }

            if(isset($tree[$key])){
                $this->tree[$key] = $tree[$key];
            }
        }

        if(empty($this->tree)){
            throw new \Exception('Não foi possivel organizar a ordem de carregamento 
            do template do sistema. Verifique se existe algum erro na montagem da ordem.');
        }
    }

    public function load()
    {
        $path = $this->template->getPath();
        $viewsDir = $this->viewConfig->getViewDirectory();
        $viewsExt = $this->viewConfig->getExtension();

        $subfolder = $this->template->getSubfolder();

        $viewPath = 'app'.self::$DS.$viewsDir.self::$DS;

        $viewData = $this->template->getVars();

        if(!isset($this->tree['view']))
        {
            throw new \Exception('Não foi configurada a areá que será incluida na view do controller, certifique-se que exista a chave "view" no array de arvore do template ');
        }

        if(!$this->template->getTemplate()){
            $view = $this->tree['view'];

            $this->tree = ['view' => $view];
        }

        $add_css = $viewData['css'];
        $add_js = $viewData['js'];

        unset($viewData['js'],$viewData['css']);

        $errorNoFile = null;

        foreach ($this->tree as $key => $viewPart){
            if(is_numeric($key)){
                continue;
            }

            $view = $viewPath . $subfolder . $viewPart . $viewsExt;

            if($key === 'view'){
                $view = $viewPath . $path.self::$DS . $viewPart . $viewsExt;
            }

            if(file_exists($view)){
                if(isset($viewData[$key])){
                    extract($viewData[$key], EXTR_PREFIX_ALL, $this->viewConfig->getViewVarsPrefix());
                } else {
                    extract($viewData, EXTR_PREFIX_ALL, $this->viewConfig->getViewVarsPrefix());
                }

                include_once $view;
            } else {
                $errorNoFile .= $viewPart . $viewsExt.', ';
            }
        }

        if($errorNoFile){
            $errorNoFile = substr($errorNoFile,0,-2);
            throw new \Exception('Um ou mais arquivos do template não foram localizados: '. $errorNoFile);
        }

        exit;
    }

    public function partial(string $view, array $params = [])
    {
        $this->partial->partial($view,$params);
    }

}