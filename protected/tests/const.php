<?php

    class Padre
    {
        static protected $_conn = array();
        
        public static function getInstance()
        {
            $class = get_called_class();
            if (isset(self::$_conn[$class])){
                return self::$_conn[$class];
            } else {
                self::$_conn[$class] = new $class();
                return self::$_conn[$class];
            }
        }
        
        public function getClass()
        {
            return get_called_class();
        }
    }

    class Hijo extends Padre
    {
    }

    $hijo = Hijo::getInstance();
    echo '<br />';
    
    echo 'Hijo: '.$hijo->getClass();
    //echo 'Hijo: ' . Hijo::$_className;

    echo '<br />';
    $padre = Padre::getInstance();
    
    echo '<br />';
    echo 'Padre: '.$padre->getClass();
    //echo 'Padre: '.  Padre::$_className;
    
    $hijo2 = Hijo::getInstance();
    echo '<br />';
    echo 'Hijo2: '.$hijo2->getClass();
    //echo 'Hijo: '. Hijo::$_className;
    echo '<br />';
?>