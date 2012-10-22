<?php
/*
* CONNECTION
* Object with the connection data; host, user, root, password.
* 
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
class CConnectionData extends CBaseClassSingleton
{
    //protected static $_connection;
    
    protected $_host, $_user, $_password, $_DB, $_dataProvider, $_charset;
    
    protected function __construct()
    {
        $this->_host = 'localhost';
        $this->_user = 'root';
        $this->_password = '';
        $this->_DB = 'ecommerce';
        $this->_charSet = 'utf8';
        $this->_dataProvider = 'CMySqlProvider'; // Default data provider (name of the class)
        
    }
}
?>