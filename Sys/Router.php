<?php
namespace Sys;

/**
* Router
*/

class Router
{
    
    private $routes;
    private $baseRoute;
    
    public function __construct() {
        $this->routes = array();
        $this->baseRoute = '';
    }


     /**
     * Parse the url
     */   
    public function loadRoutes()
    {
        $qry = $_SERVER['SCRIPT_NAME'] . (!empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '');
        $qryStr = str_replace($qry, '', $_SERVER['REQUEST_URI']);
     
        $this->routes = array_filter(explode('/', $qryStr));
        $this->baseRoute = reset($this->routes);
            
        return (count($this->routes) > 0) ? true : false;
    }
        
    public function getRoutes()
    {
        return $this->routes;
    }
        
    public function getBaseRoute()
    {
        return $this->baseRoute;
    }
    
    
    /**
     * 
     * @return String the relative path to the view
     */    
    public function getViewPath()
    {
        $path = STORE_PATH . 'views' . DS . $this->baseRoute;
        // Load the file specified in the folder or index.php if no file specified
        if (is_dir($path))
        {
            $fileName = null;
            if (count($this->routes) > 1)
            {
                $fileName = $this->routes[1];
            } else if (count($this->routes) == 1)
            {
                $fileName = $this->baseRoute;
            } else {
                $fileName = 'index';
            }              
                
            if ( !is_null($fileName) && file_exists($path . DS . $fileName.'.php') )
                return ($path . DS . $fileName.'.php');     
            elseif (file_exists($path . DS . 'index.php'))
                return ($path . DS . 'index.php'); 
        }
        return STORE_PATH  . 'views' . DS . 'altheaders' . DS . '404.php';
    }    
}