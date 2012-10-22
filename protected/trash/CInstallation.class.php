<?php
    class CInstallation extends CBaseClassSingleton
    {
        private $_db;
        
        final private function __construct() {
            $this->_db = CDbConnection::getConnection();
        }
        
        final public function createEstructure($type=NULL, $onlyOneClassName = NULL)
        {
            if(is_null($type)) {
                throw new CustomException(Parse::text("You don't define what do you want create"));
            }
            
            $this->createDatabaseStructure();
            
            // Show all tables
            $list = array();
            $query = "SHOW TABLES";
            if (!$result = $this->_db->query($query)) {
                throw new CustomException(Parse::text('Can not execute the query '));
                return NULL;
            }
            
            if ($result->num_rows < 1) {
                throw new CustomException(Parse::text('The dabase ir empty'));
                return NULL;
            }

            $path = TO_CREATE_PATH.$type.'.php';
            if (!is_readable($path)) {
                throw new CustomException(Parse::text('The base file to create the {type} no exists', array('type'=>$type)));
                return NULL;
            }

            $caseSensitive = true;

            $replace = array();

            $type = strtolower(trim($type));

            // Roam the tables of database
            while($rs = $result->fetch_array())
            {
                $tableName = $rs[0];
                $className = Parse::classNameEncode($tableName);

                switch ($type)
                {
                    case 'controller':
                        $className .= Config::settings()->postfixController;
                        $pathDestination = CONTROLLERS_DEFAULT_PATH;
                        break;

                    case 'model':
                        $pathDestination = MODELS_DEFAULT_PATH;
                        break;

                    default :
                        throw new CustomException(Parse::text('Unknown type to create'));
                        return NULL;
                        break;
                }

                $replace = array(
                    '{className}' => $className,
                    '{tableName}' => $tableName,
                );

                $target = $pathDestination.ucfirst($className).'.php';

                if (createFile($path, $replace, $caseSensitive, $target)) {
                    array_push($list, Parse::Text('The {type}.php file was created successfully', array('type'=>$className)));
                }else{
                    array_push($list, Parse::Text('The {type}.php file could not be created', array('type'=>$className)));
                }
            }

            $toReturn = '';
            $toReturn .= '<ul id = "createMVC">';
            foreach ($list as $lm)
            {
                $toReturn .= '<li>'.$lm.'</li>';	
            }
            $toReturn .= '</ul>';

            echo $toReturn;
        }
        
        final private function createDatabaseStructure() {
            
        }
        
        final private function loadFile($sFilename, $sCharset = 'UTF-8')
        {
            if (floatval(phpversion()) >= 4.3) {
                $sData = file_get_contents($sFilename);
            } else {
                if (!file_exists($sFilename)) return false;
                $rHandle = fopen($sFilename, 'r');
                if (!$rHandle) return false;

                $sData = '';
                while(!feof($rHandle))
                    $sData .= fread($rHandle, filesize($sFilename));

                fclose($rHandle);
            }
            //if ($sEncoding = mb_detect_encoding($sData, 'auto', true) != $sCharset)
                //$sData = mb_convert_encoding($sData, $sCharset, $sEncoding);

            return $sData;
        }

        final private function createFile($path, array $replace, $caseSensitive = true, $target = NULL)
        {
            $path = Parse::path($path);
            $target = Parse::path($target);

            //Initialize variable
            $text = "";

            // If $caseSenitive is true, use 'str_replace' function, else, use 'str_ireplace' function-
            $function_replace = $caseSensitive?'str_replace':'str_ireplace';

            //Open file only read.
            $fp = @fopen($path,"r");

            if (!$fp)
                return false;

            //Leemos linea por linea el contenido del archivo
            while ($line = fgets($fp,1024))
            {
                foreach ($replace as $search => $change)
                {
                    //Sustituimos las ocurrencias de la cadena que buscamos
                    $line = $function_replace($search,$change,$line);
                }

                //Anadimos la linea modificada al texto
                $text .= $line;	
            }

            if (file_exists($target)) // If exists target.
            {
                $tempTarget = explode('\\', $target);
                $tempFile = explode('.', $tempTarget[count($tempTarget) - 1]);

                if (isset($tempFile[count($tempFile) - 2]))
                    $tempNameKey = count($tempFile) - 2;
                else
                    $tempNameKey = count($tempFile) - 1;

                $tempFile[$tempNameKey] = date('Y-m-d H-i'). ' - '. $tempFile[$tempNameKey];

                $tempFile = implode('.', $tempFile);
                $tempFile .= '.old';

                $i = 1;
                $countTempTarget = count($tempTarget);
                $targetCopy = '';

                foreach ($tempTarget as $tt)
                {
                    if ($i == $countTempTarget)
                    {
                        $targetCopy .= $tempFile;
                        break;
                    }

                    $targetCopy .= $tt.'\\';
                    $i++;
                }
                if (copy($target, $targetCopy)) // Back up file.
                    $openMode = 'c+'; // Open file to read and write (delete exist data).
                else
                    return false;
            }
            else
            {
                $openMode = 'x+'; // Create file to read and write.
            }

            $model=fopen($target,$openMode);
            fputs($model,$text);
            fclose($model);

            return true;
        }
    }
?>