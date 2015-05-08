<?php
namespace Sys;
use Sys\Initializer as Ini;
/**
 * Description of Sessions
 *
 * @author PG
 */
class Sessions {
    
    protected $_parent;
    private $storageType = '';
    private $storageEngine;
    private $session;
    private $id;
    private $customer;
    private $customerData = array();
    
    public function __construct($Sb)
    {
        echo 'SESS<pre>';
        $this->_parent = $Sb;

        if ( Ini::getConfig('sessions') )
            $this->storageType = Ini::getConfig('sessions')->type;   

        $this->session = $_SESSION;
        $this->id = session_id();
        //$this->id = Memcached::get('memc.sess.key.'.session_id());
        print_r($this);
        echo '</pre>END SESS';
    }   
    
    public function storeSession()
    {
        if ($this->storageType == 'service')
        {
            $url = $this->_parent->sessionService->getUrl();
            $sess = $this->_parent->sessionService->setSoapRequest($url, $this->getRequestAddSessionEntryXml())->invoke(); 
            echo 'hi '. session_id() . '; '.$this->_parent->storeName;
            var_dump($sess);

        } else if ($this->storageType == 'default')
        {
           // php session storage only 
        } else if ($this->storageType == 'memcache')
        { 
            // try getting the cache class from here 
            $this->storageEngine = new \Sys\Caching();
            $this->storageEngine->cache($this);
        }        
    }
    

    public function saveSession()
    {
        
    }    
    
    public function setCustomerData($key, $value)
    {
        $this->customerData[$key] = $value;
    }
 
    public function getCustomerData($key)
    {
        return (isset($this->customerData[$key]) ? $this->customerData[$key] : false);
    }
    
    /**
     * Get session entries from service storage
     */
    public function getSessionHistory()
    {
        $moresess = $this->_parent->sessionService->setSoapRequest($url, $this->getRequestSessionXml( $this->_parent->storeName, session_id() ))->invoke();
        var_dump($moresess);
    }
    
    /**
     * Create the xml string for adding a session entry to storage service
     * @param type $customerID
     * @return string
     */
    private function getRequestAddSessionEntryXml($customerID = null)
    {
        $url = $_SERVER["SERVER_NAME"].($_SERVER['SERVER_PORT'] == '80' ? '' : $_SERVER['SERVER_PORT']).'/'.$_SERVER["URL"];
        $qryStr = (isset($_SERVER["QUERY_STRING"])) ? $_SERVER["QUERY_STRING"] : '';
              
        $xml = '<?xml version="1.0" encoding="UTF-8"?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
            <S:Header/>
                <S:Body>
                    <ns2:saveSession xmlns:ns2="http://SBSessions/">
                       <sessionID>'.session_id().'</sessionID>
                       <url>'.$url.'</url>
                       <queryString>'.$qryStr.'</queryString>
                       <customerID>'.$customerID.'</customerID>
                       <storeID>'.$this->_parent->storeName.'</storeID>    
                    </ns2:saveSession>
               </S:Body>
           </S:Envelope>'; 
        
        return $xml;
    }
    
    /**
     * Gets all sessioninformation from storage
     * @param string $sessionID the php session id used to identify the session in storage
     * @return string
     */
    private function getRequestSessionXml($sessionID)
    {  
        $xml = '<?xml version="1.0" encoding="UTF-8"?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
            <S:Header/>
                <S:Body>
                    <ns2:getSession xmlns:ns2="http://SBSessions/">
                       <sessionID>'.session_id().'</sessionID>   
                    </ns2:getSession>
               </S:Body>
           </S:Envelope>'; 
        
        return $xml;
    }
    
}

/*
 * Response
 <?xml version="1.0" encoding="UTF-8"?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
    <S:Body>
        <ns2:sessionStoreResponse xmlns:ns2="http://StoreBright/">
            <return>Hello gkygkhgjhgjhgjnhgknhjg @ http://someurl</return>
        </ns2:sessionStoreResponse>
    </S:Body>
</S:Envelope>
 */