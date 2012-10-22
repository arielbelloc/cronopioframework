<?php
    class CInstallation extends CBaseSingleton
    {

        function createEstructure($type=NULL, $onlyOneClassName = NULL)
        {
            $db = CDbConnection::getConnection();

            if(is_null($type)) {
                throw new CustomException(Parse::text("You don't define what do you want create"));
            }

            // Show all tables
            $list = array();
            $query = "SHOW TABLES";
            if (!$result = $db->query($query)) {
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
    }
?>