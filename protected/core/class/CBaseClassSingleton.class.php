<?php
/**
* BASE CLASS SINGLETIN
* The base class for classes that have singleton pattern design.
* IMPORTANT NOTE: This class extends the CBaseClassReadOnly class.
* 
* @Original Code: http://blog.osusnet.com/2009/05/28/patron-singleton-con-herencia-en-php/
* @Author ariel.belloc
*/
abstract class CBaseClassSingleton extends CBaseClassReadOnly
{
    protected static $_instance = array();
    
    final public static function getInstance($class = NULL)
    {
        if (is_null($class))
        {
            $class = get_called_class();
        }
        
        if(isset(self::$_instance[$class])){
            return self::$_instance[$class];
        }else{
            self::$_instance[$class] = new $class();
            Debug::addDebugParams(array('Classes instanced with Singleton Pattern Design' => $class));
            return self::$_instance[$class];
        }
    }
    
    final public function getClassName()
    {
        return get_called_class();
    }
}

?>
