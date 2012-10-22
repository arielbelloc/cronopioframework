<?php
/**
* BASE CLASS READ ONLY
* The base class for classes that were their properties are read only.
* @author ariel.belloc
*/
abstract class CBaseClassReadOnly
{
    
    public function __get($name)
    {
        $name = '_'.$name;
        
        if (property_exists($this, $name)) {
            return $this->{$name};
        }else{
            throw new CustomException(Parse::text('Get error: the property ${name} no exists.', array('name' => $name)));
        }
    }
    
    public function __set($name, $value) {
        throw new CustomException(Parse::text('Set error: the property ${name} is read only.', array('name' => $name)));
    }
}

?>
