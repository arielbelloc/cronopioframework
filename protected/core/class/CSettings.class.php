<?php
/*
* SETTINGS
* Object with the setting data.
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/

class CSettings extends CBaseClassSingleton
{
    protected 
        $_debugEnabled,
        $_dataProvider,
        $_defaultLanguage,
        $_productionStage,
        $_debugRequestAction,
        $_debugRequestPath,
        $_decimals,
        $_decimalSeparatorToShow,
        $_decimalSeparatorToWrite,
        $_thousandSeparator,
        $_postfixController,
        $_defaultMethod,
        $_defaultView,
        $_defaultController,
        $_defaultModule,
        $_trueValueToWrite,
        $_falseValueToWrite,
        $_timeFormat,
        $_dateFormat,
        $_dateTimeFormat,
        $_theme,
        $_titleSite,
        $_subTitleSite,
        $_rowPerPage;
    
    protected function __construct()
    {
        $this->_debugEnabled = FALSE;
        
        $this->_defaultLanguage = 'es_ar'; // Default language
        $this->_theme = 'default'; // Default theme
        $this->_titleSite = '';
        $this->_subTitleSite = '';

        $this->_debugRequestAction = 'debug_request.php'; // name of the file to debug the request of a form.
        $this->_debugRequestPath = FRAMEWORK_URL.'debug_pages'.DS; // Path of the file $settings['debug_request_action']

        $this->_decimals = 2;
        $this->_decimalSeparatorToShow = ',';
        $this->_decimalSeparatorToWrite = '.';
        $this->_thousandSeparator = '.';
        
        $this->_debugRequestAction = 'index.php'; // name of the file to debug the request of a form.
        $this->_debugRequestPath = FRAMEWORK_URL; // Path of the file $settings->debugRequestAction']
        $this->_postfixController = 'Controller'; // Postfix to controller class.
        $this->_defaultMethod = 'index'; // Name of the default method.
        $this->_defaultView = 'index';
        $this->_defaultController = 'index'; // Name of the default Controller.
        $this->_defaultModule = 'public';
        
        $this->_trueValueToWrite = 1; // The true value to the boolean fields.
        $this->_falseValueToWrite = 0; // The false value to the boolean fields.
        
        $this->_dateFormat = 'Y-m-d';
        $this->_timeFormat = 'H:m:s';
        $this->_dateTimeFormat = $this->_dateFormat. ' ' . $this->_timeFormat;
        
        $this->_rowPerPage  = 10;
    }
    
    final public function setTheme($theme) {
        $this->_theme = $theme;
    }
    
    final public function setDefaultController ($controller) {
        $this->_defaultController = $controller;
    }
    
    final public function setDefaultMethod ($method) {
        $this->_defaultMethod = $method;
    }
}   
?>