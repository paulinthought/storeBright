<?php
namespace Sys;
/*
 * 
 */

/**
 * Description of View
 *
 * @author PG
 */
class View {
    //put your code here
    public $content = '';
    //private $Sb;
    private $controller;
    private $view;
    //private $altHeaders = array(404 => '');
    
    public function __construct($controller, $view) {
        
        $this->view = $view;   
        $this->controller = $controller; 
        // We don't want $Sb in the views since it breaks MVC
        //$this->Sb = $Sb;
        
        if (!is_readable($view))
        {
            // replace exception with header 404
            header("Status: 404 Not Found");
            throw new \Exception('No index file present in view folder. Tried to find file '.$view);
        }
        $this->loadLayout();
        
    }
    
    private function loadLayout($layout = 'index.php')
    {
        require_once STORE_PATH . DS . 'views' . DS . 'layouts' . DS . $layout;
    }
    
    public function loadView(){
        require_once $this->view;
    }
    
    public function loadModule($module){
        if (is_readable(STORE_PATH.DS.'views'.DS.'modules'.DS.$module))
        {
            require_once STORE_PATH.DS.'views'.DS.'modules'.DS.$module;
        }
    }
}

?>
