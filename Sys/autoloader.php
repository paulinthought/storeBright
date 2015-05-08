<?php
/**
 * Class should be in the global namespace    
 * $classLoader = new autoLoader('SB\Sys\Store', 'Model', 'Filename if different from Model');
 * $classLoader->register();
 *
 * @author PG
 */
namespace Sys;

class autoLoader
{
    private $fileExtension = '.php';
    private $namespace;
    private $includePath;
    private $namespaceSeparator = '\\';
    private $fileName;

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $ns The namespace to use. no leading slash
     * @param string $includePath The folder path to the file
     * @param string $fileName If not provided fileName defaults to the same name as the last folder in the include path 
     * The filename param doesn't require a .php suffix
     */
    public function __construct($ns = null, $includePath = null, $fileName = null)
    {
        spl_autoload_extensions($this->fileExtension); 
        $this->namespace = $ns;
        //var_dump($ns, $includePath, $fileName);
        $this->setIncludePath($includePath);
        $this->fileName = $fileName;
    }

    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->namespaceSeparator = $sep;
    }
   
    
    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function getNamespaceSeparator()
    {
        return $this->namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath
     */
    private function setIncludePath($includePath)
    {
        $this->includePath = '';
        if (substr($includePath, -1) !== '/' && !empty($includePath)) {
            $this->includePath = $includePath . DS;
        }      
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePath()
    {
        return $this->includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     * 
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    
    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     *
     * @return void
     */
    private function loadClass()
    {
        echo $this->includePath . $this->fileName . $this->fileExtension . ' is loading. ';
        try {
            require_once $this->includePath . $this->fileName . $this->fileExtension; 
        } catch (\Exception $e) {
           var_dump($e);
        }
    }
    
    /**
     * 
     * @return void
    
    private function preparePath()
    {   
        $includeParts = explode($this->namespaceSeparator, $this->includePath);

        // The first included file must always have the same name as directoy and module
        if (!isset($this->fileName)) 
        {
        //    $this->fileName = end($includeParts);
        }
        // Create the defined path constant as set in store/index.php from the namespace provided
        //$namespaceParts = explode($this->namespaceSeparator, $this->namespace);
   var_dump($this->fileName);     
        $pathIdentifier = strtoupper($namespaceParts[1]) . '_PATH';
   
        $baseRequirePath = constant($pathIdentifier); 

        return $baseRequirePath;
    } */
}