<?php

namespace Sys;

require_once  'autoloader.php';
require_once STORE_PATH . 'Sb.php';

/**
* Initializer
* 
* Initializer loads service files.
* It instantiates services classes and passes them into Sys\Sb;
* it instantiates Store\Sb
* Store\Sb instantiates 1 of each module class and passes Sys\Sb into it;
*/

Class Initializer
{

	/**
	* Max file size for config json text
	*/
	const MAX_CONFIG_SIZE = 1024; // bytes

	/**
	* Store config parsed from json config
	*/
        static public $storeName = '';
        static public $serviceConnections = array();
        static public $storeModules = array();
        /**
         * Loaded from Sb\Sys\sb and Sb\Store\Sb 
         * @var type 
         */
        static public $autoLoadedClasses = array(); // array of names of module objects
        static public $autoLoadedServices = array(); // array of names of service connection objects, useful for getting the service object
	
        
        static private $storeConfig; 
        static private $configParts = array();
        static private $router;
        static private $Sb; // The $Sb object which wraps service and system classes
	static private $modelController; // Array containing store model and controller
        
        /**
         * Setup the store, parse the config and load object files
         * 
         */ 
        static public function init()
	{
		// store configs
		self::parseConfig();

                // Get the root objects from the json config
                self::$configParts =  get_object_vars(self::$storeConfig);
               
                // storeName is 1st object name
                self::$storeName = key(self::$configParts);
                
                // servicenames
                // only add services if the module value services exists
                if ($key = array_search('services', self::$storeConfig->{self::$storeName}->modules))
                {
                    self::$serviceConnections = self::$configParts['services'];
                    unset(self::$storeConfig->{self::$storeName}->modules[$key]);
                }

                // get the stores modules from the config
                self::$storeModules = self::$storeConfig->{self::$storeName}->modules;
                
                // Load required system classes
                self::loadModule('Sysutils', null, '\Sys');
                self::loadModule('Router', null, '\Sys');
                self::loadModule('Sb', null, '\Sys');
                self::loadModule('Sb', STORE_PATH, '\Store');
                
                // wrapper class for services of sys objects
                $SbSys = new \Sys\Sb();
                
                // Set up the default Sb store object which contains refs to all other loaded objects
                self::$Sb = new \Store\Sb($SbSys);
                
                // load store pages routes manager
                self::$router = new \Sys\Router();
                self::$router->loadRoutes();
                
                // Load the \SB\Store files
                self::loadStoreFiles();
                
                // Load the files for SB\Sys\MVC View
                self::loadModule('View', null, '\Sys');
                
                // Get the path for the required view files
                $viewPath = self::$router->getViewPath();
  
echo '<br>ViewPath: '.$viewPath.'</br>';

                // Load the files for SB\Sys\MVC Model & Controller
                self::loadModule('Controller', null, '\Sys');
                self::loadModule('Model', null, '\Sys');
             
                // instantiate store model and controller
                self::$modelController = self::instantiateClasses();
                
                // instantiate Sys\View with detail of store viewpath route to load
                new \Sys\View(self::getController(), $viewPath);
            
	}

         /**
         * 
         * @return Object Sb
         */
        static public function getSb()
        {
            return self::$Sb;
        }

         /**
         * 
         * @return Store model 
         */
        static public function getModel()
        {
            return self::$modelController['Model'];
        }

         /**
         * 
         * @return Store controller
         */
        static public function getController()
        {
            return self::$modelController['Controller'];
        }
        
        /**
         * 
         * @param String $key
         * @return string config value
         */
        static public function getConfig($key = null)
        {
            if (isset($key))
            {
                if (is_array($key))
                {
                    // maybe later
                    //$combkey = implode('->', $key);
                    //return self::$configParts[${$combkey}];
                } else if (isset(self::$configParts[$key])) {
                    return self::$configParts[$key];
                }
            } else 
            {
                return self::$configParts[self::$storeName];
            }
            return false;
        }
        
        /**
	* Parses the config json text into objects
	*/	
	static private function parseConfig()
	{
            try 
            {		
                $json = '';	
                $filename = SYS_PATH.'Config' .DS.'config.json';	
                if (@$fp = fopen($filename, 'r'))
		{
                    $fs = filesize($filename);

                    if ($fs > self::MAX_CONFIG_SIZE)
                        throw new \Exception("Config too large. Data will be truncated!", 1);	

                    $json = fread($fp, self::MAX_CONFIG_SIZE);
                    fclose($fp);

		} else {
                    throw new \Exception("Config not found", 1);	
		}
	
                self::$storeConfig = json_decode($json);
		if ( json_last_error() === JSON_ERROR_NONE )
		{
                    return true;
		} else
                {
                    if (self::$storeConfig == null)
                        throw new \Exception("Config.json may be empty. ". json_last_error(), 1);
                        //$constants = get_defined_constants(true);
                        //var_dump($constants['json']);
                    throw new \Exception("Config is not valid JSON. The following error was returned. ". json_last_error(), 1);	
                }

            } catch (\Exception $e) 
            {
                echo 'bollockles' . $e;
            } 
            return false;
	}
        
        
        /**
         * Loads the routes base model and controller if they exist, otherwise loads ParentModel/ParentController
         * @return \SB\Store Model/Controller array
         */
        static private function instantiateClasses()
        {
            $classes = array('Model' => null, 'Controller' => null);
            
            $model = Sysutils::Ucase(self::$router->getBaseRoute()).'Model';
 
            $classes['Model'] = (class_exists($model)) ? new $model() : new \Store\ParentModel();
            
            $controller = Sysutils::Ucase(self::$router->getBaseRoute()).'Controller';
 
            $classes['Controller'] = (class_exists($controller)) ? new $controller() : new \Store\ParentController();
            
            return $classes;
        }
        
         /**
         * TODO: implement $allFiles
	 * Wrapper for the autoLoader class
         * @param string $path absolute path to the directory where the file is kept
         * @param string $class name of class file and class
         * @param $namespace for registering the class default is Sys
         * @param $allFiles will load all files in the given path directory, $path cant be empty, ignores $class since it'll load as well
	 */
        static public function loadModule($class = null, $path = null, $namespace = null)
        {
            // use the current directories namespace if it's not set
            if (!isset($namespace))
                $namespace = __NAMESPACE__;
  
            // load class
            $classLoader = new autoLoader($namespace, $path, $class);
            $classLoader->setNamespaceSeparator(DS);
            $classLoader->register();
                
        }

        /**
         * Loads all models and controllers in those directories by default and any 
         * further classes in models or controllers subfolders named after the baseRoute 
         */
        static private function loadStoreFiles()
        {
            // models then controllers
            foreach (array('models', 'controllers') as $mvcFolder)
            {
                $path = STORE_PATH . DS . $mvcFolder;

                $fp = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);  
                // parse models or controllers directory to load selected files
                foreach ($fp as $fileinfo) 
                {
                    // parse files in parent models or controllers dir
                    if($fileinfo->isFile())
                    {
                       $classLoader = new \Sys\autoLoader('\Store', $mvcFolder , substr($fileinfo->getFilename(), 0, -4));
                       $classLoader->setNamespaceSeparator(DS);
                       $classLoader->register();
                    // else parse any dirs equal to base route name   
                    } else if($fileinfo->isDir() && strtolower($fileinfo->getFilename()) == self::$router->getBaseRoute() ) 
                    { 
                        $sfp = new \FilesystemIterator($fileinfo->getPathInfo().DS.$fileinfo->getFilename(), \FilesystemIterator::SKIP_DOTS);
                        
                        echo 'This Dir is '. $fileinfo->getPathInfo().DS.$fileinfo->getFilename();
                        // Parse all files in directories named after baseRoute
                        foreach ($sfp as $subfolderfileinfo) 
                        {
                            $classLoader = new autoLoader('\Store', $mvcFolder.DS.$fileinfo->getFilename() , substr($subfolderfileinfo->getFilename(), 0, -4));
                            $classLoader->setNamespaceSeparator(DS);
                            $classLoader->register();
                        }
                    }
                }
            }
        }
        
        /**
         * Loads a view file
         * @param type $baseRoute same as views folder
         * @param type $fileName without .php extension
         */

        

        
        
        /**
         * 
         * @param type string fully qualified namespaced $className
         * @return type Object 
        
        static public function getModule($className)
        {    
            if (isset(self::$moduleObjects[$className]))
                return self::$moduleObjects[$className];
        }
         */
        
	/*
	static public function adminInit()
	{
		// store configs
		self::getConfig();

		// store routes

		// pass on to store views
		//include (ADMIN.DS.'index.php');
	}
        */
}
echo 'nofok';
