<?php
/*
* CONNECTION
* Object with the connection data; host, user, root, password.
* 
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
class ConnectionData extends CConnectionData
{
    protected function __construct()
    {
        $this->_host = 'localhost';
        $this->_user = 'root';
        $this->_password = '';
        $this->_DB = 'ecommerce';
        $this->_charset = 'utf8';
        $this->_dataProvider = 'CMySqlProvider'; // Default data provider (name of the class)
        
        /*
        * IF YOU WANT CREATE NEW PARAMS, IT MUST BE DECLARED HOW PROTECTED.
        */
    }
}
?>