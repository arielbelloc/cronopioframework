<?php

    // Gestor de Erroes
    function myErrorHandler($code, $error, $file = NULL, $line = NULL) {
        throw new CustomException($error . ' encontrado en '. $file.', línea '.$line);
    }
   
	function require_all_folder($folder)
	{
		$inc = glob($folder . '*.php');
		foreach ($inc as $filename) 
		{ 
		    addFile($filename); 
		}
	}
	
	/*
	* @Original script: http://www.desarrolloweb.com/articulos/listar-directorios-subdirectorios-php.html
	*/
	function list_folders($path, array $arrayReturn = NULL){ 
		
		if (is_null($arrayReturn)) // If $arrayReturn is not define.
			$arrayReturn = array(); // Define array.

		// open a directory and list folder into array (recursive).
		if (is_dir($path)) {
		  if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
				//This line is used to list all (files and folders)
				//echo "<br>Name on file: $file : Es un: " . filetype($path . $file);
				if (is_dir($path . $file) && $file!="." && $file!=".."){
					//solo si el archivo es un directorio, distinto que "." y ".."
					//echo "<br>Directorio: $path$file";
					array_push($arrayReturn, $path.$file);
					list_folders($path . $file . "/", $arrayReturn);
		        }
			}
			closedir($dh);
			return $arrayReturn;
		}
		}else
			//echo "<br>No es ruta valida";
			return NULL;
	}
	
    function count_substr($string, $subString){
        return count(explode($subString, $string)) - 1 ;
    }
    
    function addFile($path = NULL)
    {
        if (!isset($path)) {
            throw new CustomException(Parse::text('Not set any path'));
        }
        if (is_readable($path)) {
            return require_once $path;
            if (class_exists('Debug')) {
                if (class_exists('Parse')) {
                    $text = Parse::text('Add file');
                }else{
                    $text = 'Add file';
                }
                  
                Debug::addDebugParams(array($text => $path));
            }
        }  else {
            throw new CustomException(Parse::text('Not found the file path {path}', array('path' => $path)));
        }
    }


    if (!function_exists('get_called_class')){
        function get_called_class(){
            $bt = debug_backtrace();
            $lines = file($bt[1]['file']);
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/', $lines[$bt[1]['line']-1], $matches);
            return $matches[1];
        }
    }
    if (!function_exists('memory_get_usage')){
        function memory_get_usage() {
            $pid = getmypid();
            exec("ps -o rss -p $pid", $output);
            return $output[1] * 1024;
        }
    }
?>