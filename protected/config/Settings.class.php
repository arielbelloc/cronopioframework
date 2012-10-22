<?php
/*
* SETTINGS
* Object with the setting data.
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/

class Settings extends CSettings
{
    
    protected function __construct()
    {
        parent::__construct();

        $this->_debugEnabled = true;
        $this->_defaultLanguage = 'es_ar'; // Default language
        $this->_theme = 'default'; // Default theme
        $this->_productionStage = true; // Define if are working in production stage or not. (if is true, load the debug class)

        $this->_decimals = 2;
        $this->_decimalSeparatorToShow = ',';
        $this->_decimalSeparatorToWrite = '.';
        $this->_thousandSeparator = '.';

        $this->_titleSite = 'Cronopio Framework';
        $this->_subTitleSite = 'The first MVC framework created by Ariel Belloc';
        
        $this->_rowPerPage  = 10;
        
        /*
        * IF YOU WANT CREATE NEW PARAMS, IT MUST BE DECLARED HOW PROTECTED.
        */
    }
}   
?>