<?php
    class CParams extends CWrappers {
        
        final public static function texts($key = NULL) {
            
            global $texts;
            
            if (!isset($key)) {
                return $texts;
            }else{
                if (isset($texts[$key])) {
                    return $texts[$key];
                }else{
                    return NULL;
                }
            }
        }
                
        final public static function languages($key = NULL)
        {
            global $languages;

            if (is_null($key)) {
                return $languages;
            }else{
                if (isset($languages[$key])) {
                    return $languages[$key];
                }else{
                    return NULL;
                }
            }
        }
        
        final public static function modules($key = NULL)
        {
            global $modules;
            
            if (is_null($key)) {
                return $modules;
            }else{
                if (isset($modules[$key])) {
                    return $modules[$key];
                }else{
                    return NULL;
                }
            }
        }
        
        final public static function moduleByFolder($folder = NULL)
        {
            if (!isset($folder)) {
                return NULL;
            }
            
            global $modules;
            
            $toReturn = NULL;
            
            foreach ($modules as $key => $values)
            {
                if ($values['folder'] == $folder)
                {
                    $toReturn = $values;
                    break;
                }
            }

            return $toReturn;
        }
        
        final public static function ModuleFolderByKey($ModuleKey = NULL)
        {
            if (!isset($ModuleKey)) {
                return NULL;
            }
            
            global $modules;
            
            if (isset($modules[$ModuleKey])) {
                if (isset($modules[$ModuleKey]['folder'])) {
                    return $modules[$ModuleKey]['folder'];
                }else{
                    throw new CustomException(Parse::text('Not set folder for the module'));
                }
            }else{
                return NULL;
            }
        }
        
        final public static function cssClass($key = NULL, $withAttribute = FALSE)
        {
            global $cssClass;
            
            if (!isset($key)) {
                return $cssClass;
            }else{
                if (isset($cssClass[$key])) {
                    if ($withAttribute) {
                        return 'class = "'.$cssClass[$key].'"';
                    }  else {
                        return $cssClass[$key];
                    }
                }else{
                    return NULL;
                }
            }
        }
        
        final public static function cssId($key = NULL, $withAttribute = FALSE)
        {
            global $cssId;
            
            if (!isset($key)) {
                return $cssId;
            }else{
                if (isset($cssId[$key])) {
                    if ($withAttribute) {
                        return 'class = "'.$cssId[$key].'"';
                    }  else {
                        return $cssId[$key];
                    }
                }else{
                    return NULL;
                }
            }
        }
    }

?>
