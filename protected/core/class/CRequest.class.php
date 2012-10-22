<?php
/*
* REQUEST (SINGLETON)
* Parse the URI and separates MODULE, CONTROLLER, METHOD and PARAMETERS.
*
* @Original Author: Jaisiel Delance | www.dlancedu.com
* @Original code: http://www.youtube.com/user/dlancedu
* 
* @Adaptation: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/

class CRequest extends CBaseClassSingleton
{
    /****************/
   /*  PROPERTIES  */
  /****************/

    /*
    * MODULE
    * The name of module
    * Type: String
    */
    protected $_module;
    
    /*
    * CONTROLLER
    * The name of controller
    * Type: String
    */
    protected $_controller;
    protected $_method;
    protected $_params;
    
    final protected function __construct()
    {
        if(isset($_GET['url'])){
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);
            
            $tempRow = array_shift($url); // First row of array
            $modulesList = Params::moduleByFolder($tempRow);
            
            if (isset($modulesList)) // If exist the module
            {
                $this->_module = $tempRow; // Assign module name
                $this->_controller = ucfirst(array_shift($url)); // Assign the controler name
                Config::settings()->setDefaultController($modulesList['defaultController']);
                Config::settings()->setDefaultMethod($modulesList['defaultMethod']);
            }else{
                $this->_controller = ucfirst($tempRow);
            }
            
            $this->_method = strtolower(array_shift($url));
            $this->_params = $url;
        }

        if(!$this->_module){
            $this->_module = Config::settings()->defaultModule;
        }
        
        if(!$this->_controller){
            $this->_controller = Config::settings()->defaultController;
        }
        
        if(!$this->_method){
            $this->_method = Config::settings()->defaultMethod;
        }
        
        if(!isset($this->_params)){
            $this->_params = array();
        }

        define('MODULE_PATH', FRAMEWORK_PATH . MODULES_FOLDER . $this->_module . DS);
            require_all_folder(MODULE_PATH . 'extends' . DS); // Define the general settings
            require_all_folder(MODULE_PATH . MODELS_FOLDER); // Define the general settings
    }
}

?>