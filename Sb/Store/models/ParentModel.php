<?php

/*
 * All models in the base Store/Models directory are loaded on all pages. Subdirectoried models will only load on the specific view page load
 */

class ParentModel extends \SB\Sys\Model
{

        public function __construct()
	{
            parent::__construct();
            echo 'Loaded ParentModel<pre>';

            print_r($this->Sb);
            echo '</pre>End ParentModel<br>';      
	}
        
        
}
?>
