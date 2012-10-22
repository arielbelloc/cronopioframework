<?php
class CConfig extends CWrappers {
    
    final public static function settings(){
        return Settings::getInstance();
    }
    
    final public static function conectionData(){
        return ConnectionData::getInstance();
    }
    
}
?>
