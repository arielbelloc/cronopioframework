<?php
	/*
	* INSTALLATION CLASS
	* Create and update the installation parameters.
	*
	* @Author: Ariel Belloc
	* @Contact: arielbelloc@gmail.com
    * @Last modified: 2012-10-12
	*/
    class CInstallation extends CBaseClassSingleton
    {
      /************************/
	 /*  PRIVATE PROPERTIES  */
	/************************/
        
        /*
		* DATABASE
		* Type: Instance of CDbConnection
		*/
        private $_db;
        
        /*
		* TABLE LIST
		* Type: Array. Table ID => Table Name
		*/
        private $_tables;

      /*****************/
	 /*  CONSTRUCTOR  */
	/*****************/
        final protected function __construct() {
            $this->_db = CDbConnection::getConnection();
            $this->_tables = $this->getTables();
        }
        
      /*******************/
	 /*  PUBLIC METHOD  */
	/*******************/
        
        /*
		* CREATE MVC STRUCTURE
		* Make a model and controller.
		*
		* @params:
         *  $type = String. model or controller.
		* @return: HTML.
        * On error: Return NULL
		*/
        final public function createMVCStructure($type=NULL, $onlyOneClassName = NULL)
        {
            if(is_null($type)) {
                throw new CustomException(Parse::text("You don't define what do you want create"));
            }
            
            $this->createDatabaseStructure();
            
            $list = array();
            
            $path = TO_CREATE_PATH.$type.'.php';
            if (!is_readable($path)) {
                throw new CustomException(Parse::text('The base file to create the {type} no exists', array('type'=>$type)));
                return NULL;
            }

            $caseSensitive = true;

            $replace = array();

            $type = strtolower(trim($type));

            // Roam the tables of database
            while($rs = $result->fetch_assoc())
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
        
        /*
		* CREATE AND UPDATE DATABASE STRUCTURE
		* It's take the database structure and create automatically a form for each table with following parameters:
         * Table params:
            * Table caption: A string with the description caption of the table.
            * Table description: A string with the description of the table.
         * Fields Params:
            * Field Type: The type of field.
            * Caption: A string with the description caption of the field.
            * Order: The order of the field in the form.
            * Required: Define if the field is required or not.
            * Field description: The description of the field.
		* 
        * NOTE: This method save de database structure in 2 system tables: system_database_structure and system_tables_structure
        * 
		* @params:
         * $tableId = Integer. The id of the table to modify the parameters. If is null, show all tables.
		* @return: HTML.
		*/
        final public function createDatabaseStructure($tableId = NULL)
        {
            /*
            $this->_db->query('TRUNCATE system_database_structure');
            $this->_db->query('TRUNCATE system_tables_structure');
            * 
            */
            ?>
<nav>
            <?php
            $this->menu();
            ?>
</nav>
            <?php
            $this->saveChanges(); // Call saveChanges method
            
            // Define a part of name for the id attribute of the tag input of the table parameters
            $databaseHtmlId = 'database_';
            
            // Define a part of name for the id attribute of the tag input of the fields parameters
            $tableHtmlId = 'table_';
            
            if (isset($tableId)) { // If exists the $tableId parameter
                $tableId = (int) $tableId; // Transform parameters in integer
                
                // Search the name of the select table.
                $tableName = $this->_db->rowbyQuery('SELECT table_name FROM system_database_structure WHERE id = ' . $tableId);
                
                $tablesToShow = array($tableId => $tableName->table_name); // Define the array whith the tables to show.
            }else{
                $tablesToShow = $this->_tables; // Define the array whith the tables to show.
            }

            foreach ($tablesToShow as $tableName) // For each table to show
            {   
                // Seach the parameters of the table.
                $dbData = $this->_db->rowByQuery('SELECT * FROM system_database_structure WHERE table_name = "' . $tableName . '"');
                
                // On error.
                if (!$dbData || count($dbData) != 1) {
                    Debug::addDebugParams(array($tableName . ' on system_database_structure'=>$dbData));
                    throw new CustomException('Error of database structure');
                }
                
                // Define the array with the parameters of the array.
                $formArray = array(
                    'params' => array ( // General params.
                        'title' => 'Table: ' . $tableName,
                    ),
                    'html' => array( // HTML attributes.
                        'name' => $tableName,
                        'id' => 'form_'.$tableName,
                        'method' => 'post',
                        'action' => '',
                    ),
                );
                
            /*************
            * START FORM *
            *************/
                Html::startForm($formArray['html'], $formArray['params']['title']);

                /********************
                * ID FIELD (HIDDEN) *
                ********************/
                    $tableArray = array (
                        'params' => array (
                            'fieldType' => HIDDEN_INPUT,
                        ),
                        'html' => array (
                            'name' => 'db*id',
                            'value' => $dbData->id,
                        )
                    );
                    Html::createHtmlInput($tableArray);

                /**********************
                * TABLE CAPTION FIELD *
                **********************/
                    $tableArray = array (
                        'params' => array (
                            'fieldType' => STR_FIELD,
                            'caption' => 'Table caption:',
                            'div_class' => 'table_caption',
                        ),
                        'html' => array ( // HTML attributes.
                            'name' => 'db*table_caption',
                            'id' => $databaseHtmlId . '_' . $tableName . '_caption',
                            'value' => $dbData->table_caption,
                        )
                    );
                    Html::createHtmlInput($tableArray);

                /**************************
                * TABLE DESCRIPTION FIELD *
                **************************/
                    $tableArray = array (
                        'params' => array (
                            'fieldType' => TEXT_FIELD,
                            'caption' => 'Table description:',
                        ),
                        'html' => array ( // HTML attributes.
                            'name' => 'db*table_description',
                            'id' => $databaseHtmlId . '_' . $tableName . '_description',
                            'value' => $dbData->table_description,
                        )
                    );
                    Html::createHtmlInput($tableArray);
                    unset($tableArray);

                    // Search the fields of the table
                    $query = 'SELECT * FROM ' . $tableName . ' WHERE FALSE';
                    $resultFields = CDbConnection::getConnection()->query($query);
                    
                    $count = 0; // Define the number of loops in the while sentence. Use this for define the field order.
                    while ($field = $resultFields->fetch_field()) // For each field of the table.
                    {
                        $count++;
                        $fieldType = Parse::fieldType($field); // Define the field type Id form a MySqli fetch field intance.

                        // If not exists this field in system_tables_structure table.
                        if (!$this->existFieldInTableStructure($field->name, $dbData->id)) {
                            $tableStructure = array (
                                'system_tables_structure' => array(
                                    'database_structure_id' => $dbData->id,
                                    'field_types_id' => $fieldType,
                                    'field_name' => $field->name,
                                    'field_caption' => trim(ucfirst(str_replace('_', ' ', $field->name))),
                                    'field_required' => true,
                                    'field_description' => trim(ucfirst(str_replace('_', ' ', $field->name))),
                                    'field_order' => $count,
                                ),
                            );

                            if ($fieldType == STR_FIELD) { // If is a string field
                                // Save the length of the field.
                                $tableStructure['system_tables_structure']['field_type_int_param'] = $field->length / 3;
                            }elseif ($fieldType == FOREIGN_KEY_FIELD) { // If is a foreign key field
                                // Save the id of the table.
                                $tableStructure['system_tables_structure']['field_type_int_param'] = array_search(substr($field->name, 0, strlen($field->name)-3), $this->_tables);
                            }

                            $this->_db->insert($tableStructure);
                        }

                        // Search a table data.
                        $tableData = $this->_db->rowByQuery('SELECT * FROM system_tables_structure WHERE database_structure_id = ' . $dbData->id . ' AND field_name = "' . $field->name . '"');
                        
                        // On Error
                        if (!$tableData || count($tableData) != 1) {
                            Debug::addDebugParams(array($field->name . ' on system_table_structure'=>$tableData));
                            throw new CustomException('Error of table structure');
                        }
                        
                    /*****************
                    * START FIELDSET *
                    *****************/
                        // Create several input tags, wrapping it in a fieldset tag.
                        $fieldSetName = $tableData->field_name.' field'; // Define the fieldset caption
                        $fieldsArray = array ($fieldSetName => array()); // Initialize de array to create the inputs

                        /**************
                        * ID (HIDDEN) *
                        **************/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => HIDDEN_INPUT,
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*id',
                                    'value' => $tableData->id,
                                )
                            )
                        );

                        /*************
                        * FIELD TYPE *
                        *************/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => CMB_INPUT,
                                    'caption' => 'Field Type:',
                                    'data' => $this->fieldTypeList(),
                                    'selected' => $tableData->field_types_id,
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*field_types_id',
                                    'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_type',
                                )
                            )
                        );

                        /****************
                        * CAPTION FIELD *
                        ****************/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => STR_FIELD,
                                    'caption' => 'Caption:',
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*field_caption',
                                    'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_caption',
                                    'value' => $tableData->field_caption,
                                )
                            )
                        );

                        /********
                        * ORDER *
                        ********/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => INT_FIELD,
                                    'caption' => 'Order:',
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*field_order',
                                    'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_order',
                                    'value' => $tableData->field_order,
                                )
                            )
                        );

                        /***********
                        * REQUIRED *
                        ***********/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => BOOL_FIELD,
                                    'caption' => 'Required:',
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*field_required',
                                    'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_required',
                                    'value' => $tableData->field_required,
                                )
                            )
                        );

                        /**************
                        * DESCRIPTION *
                        **************/
                        array_push($fieldsArray[$fieldSetName],
                            array (
                                'params' => array (
                                    'fieldType' => TEXT_FIELD,
                                    'caption' => 'Field description:',
                                ),
                                'html' => array (
                                    'name' => 'table*' . $tableData->field_name . '*field_description',
                                    'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_description',
                                    'value' => $tableData->field_description,
                                )
                            )
                        );

                        switch ($fieldType)
                        {
                            case STR_FIELD:
                                /*********************
                                * MAX LENGTH OF FIELD
                                *********************/
                                array_push($fieldsArray[$fieldSetName],
                                    array (
                                        'params' => array (
                                            'fieldType' => STR_FIELD,
                                            'caption' => 'Max Length of Field:',
                                        ),
                                        'html' => array (
                                            'name' => 'table*' . $tableData->field_name . '*field_type_int_param',
                                            'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_lenght',
                                            'value' => $tableData->field_type_int_param,
                                        )
                                    )
                                );
                                break;

                            case FOREIGN_KEY_FIELD:
                                /***************
                                * RELATED TABLE
                                ****************/
                                array_push($fieldsArray[$fieldSetName],
                                    array (
                                        'params' => array (
                                            'fieldType' => CMB_INPUT,
                                            'caption' => 'Related Table:',
                                            'data' => $this->_tables,
                                            'selected' => $tableData->field_type_int_param,
                                        ),
                                        'html' => array (
                                            'name' => 'table*' . $tableData->field_name . '*field_type_int_param',
                                            'id' => $tableHtmlId . '_' . $tableName . '_' . $tableData->field_name . '_type',
                                            'alt' => $field->name,
                                        )
                                    )
                                );

                                break;
                        };

                        Html::createHtmlInputsInFieldset($fieldsArray);

                    /***************
                    * END FIELDSET *
                    ***************/
                    } // |end: while ($field = $resultFields->fetch_field())|
                Html::endForm(true);
            /***********
            * END FORM *
            ***********/
            }
        } // |end: method|


      /********************/
	 /*  PRIVATE METHOD  */
	/********************/
        /*
		* MENU
		* Create a menu
		* 
        * @params: void.
		* @return: HTML.
		*/
        final private function menu()
        {
            $menu = array(
                'html' => array('class' => 'ulclass'),
                'menu' => array (
                    'home' => array(
                        'link' => FRAMEWORK_URL,
                    ),
                    'about us' => array(
                        'link' => FRAMEWORK_URL . 'aboutus',
                        'htmlItem' => array('class' => 'liHTML'),
                    ),
                    'admin' => array (
                        'html' => array('class' => 'subMenuClass'),
                        'menu' => array (
                            'countries' => array(
                                'link' => FRAMEWORK_URL . 'siteadmin/countries',
                            ),
                            'cities' => array(
                                'link' => FRAMEWORK_URL . 'siteadmin/cities',
                                'htmlItem' => array('class' => 'itemP'),
                                'html' => array('class' => 'SubitemsP'),
                                'menu' => array(
                                    'prueba 1' => array(
                                        'link' => '1.html',
                                    ),
                                    'prueba 2' => array(
                                        'link' => '2.html',
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            );
            
            Html::createMenu($menu);
        }
        
        
        /*
		* SAVE CHANGES
		* If exists post parameters, update the system_database_structure table
		* 
        * NOTE: This method save de database structure in 2 system tables: system_database_structure and system_tables_structure
        * 
		* @params: void.
		* @return: void.
		*/
        final private function saveChanges()
        {
            if (count($_POST)<1) {
                return;
            }
            
            // Define the arrays to create a insert query
            $dbParams = array();
            $tableParams = array();
            
            foreach ($_POST as $field => $value) // For each post parameter
            {
                $field = explode('*', $field); // Divide the string and create an array.
                
                if ($field[0] == 'db'){ // If is db parameter
                    $dbParams[$field[1]] = $value; // Fill array $dbParams
                } elseif ($field[0] == 'table'){ // If is table parameter
                    $tableParams[$field[1]][$field[2]] = $value; // Fill array $tableParams
                }
            } // |end: foreach|
            
            if (isset($dbParams['id'])) // if exists id db parameters
            {
                $idDb = $dbParams['id']; // if exists id db parameters
                unset($dbParams['id']); // Delete id paramater form array
                
                // Add table to update
                $dbParams = array('system_database_structure' => $dbParams);
                
                // update system_database_structure
                $this->_db->update($dbParams, 'id = ' . $idDb);
                
            } // |end: if|
            
            if (count($tableParams)>0) // if exists table parameters
            {
                foreach ($tableParams as $table_field => $params) // foreach table parameters
                {
                    if (isset($params['id']))
                    {
                        $idTable = $params['id'];
                        unset($params['id']);
                        
                        if (isset($params['field_required'])) {
                            //$params['field_required'] = Config::settings()->trueValueToWrite;
                            $params['field_required'] = TRUE;
                        }else{
                            //$params['field_required'] = Config::settings()->falseValueToWrite;
                            $params['field_required'] = FALSE;
                        }

                        $params = array('system_tables_structure' => $params);
                        $this->_db->update($params, 'id = ' . $idTable);
                    } // |end: if|
                } // |end: foreach|
            } // |end: if|
        }
        
        /*
		* EXIST TABLE IN DATABASE STRUCTURE
		* Return true or false depending if exist or not the table in system_database_structure
		* 
		* @params: tableName: string. The name of the table to search.
		* @return: boolean.
		*/
        final private function existTableInDatabaseStructure($tableName)
        {
            // Search the table
            $query = 'SELECT id FROM system_database_structure WHERE table_name = "' . trim($tableName) . '"';
            $result = $this->_db->query($query);

            if ($result->num_rows < 1) { // If not is tables in database
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        /*
		* EXIST FIELD IN TABLE STRUCTURE
		* Return true or false depending if exist or not the field in system_table_structure
		* 
		* @params: 
         * $fieldName: string. The name of the field to search.
         * $databaseStructureId: Id of table in system_database_structure
		* @return: boolean.
		*/
        final private function existFieldInTableStructure($fieldName, $databaseStructureId) {
            
            // Search the field
            $query = 'SELECT id FROM system_tables_structure WHERE field_name = "'. trim($fieldName) . '" and database_structure_id = ' . $databaseStructureId;
            $result = $this->_db->query($query);

            if ($result->num_rows < 1) { // If not is tables in database
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        /*
		* GET TABLES
		* Checked of tables. Return an array with: table Id => table name. If the table not exists, it create it.
		* 
		* @params: 
         * $fieldName: string. The name of the field to search.
         * $databaseStructureId: Id of table in system_database_structure
		* @return: boolean.
		*/
        final private function getTables()
        {
            // Search all the tables of the database less that beginning with "system_".
            $query = 'SHOW TABLES WHERE tables_in_'.Config::conectionData()->DB.' not LIKE "system_%" '; // Show all tables
            
            // On error
            if (!$result = $this->_db->query($query)) { // If fail.
                throw new CustomException(Parse::text('Can not execute the query '));
            }
            
            // If database is empty
            if ($result->num_rows < 1) { // If not there tables in database
                throw new CustomException(Parse::text('The dabase is empty'));
            }

            $toReturn = array();
            while($table = $result->fetch_array()) // For each table
            {
                if (!$this->existTableInDatabaseStructure($table[0])) { // If not exist the table in system_database_structure
                    $databaseStructure = array (
                        'system_database_structure' => array(
                            'table_name' => $table[0],
                            'table_caption' => trim(ucfirst(str_replace('_', ' ', $table[0]))),
                            'table_description' => trim(ucfirst(str_replace('_', ' ', $table[0]))),
                        ),
                    );
                    $this->_db->insert($databaseStructure);
                } // |end: if|
                
                // Search the table.
                $dbData = $this->_db->rowByQuery('SELECT * FROM system_database_structure WHERE table_name = "' . $table[0] . '"');
                
                // On error
                if (!$dbData || count($dbData) != 1) {
                    Debug::addDebugParams(array($tableName . ' on system_database_structure'=>$dbData));
                    throw new CustomException('Error of database structure');
                }
                
                // Fill array to return
                $toReturn[$dbData->id] = $table[0];
            } // |end: while($table = $result->fetch_array())|
            
            return $toReturn;
        }
        
        /*
		* FIELD TYPE LIST
		* return an array: id of field type => description of field type.
		* 
		* @params: void.
		* @return: array.
		*/
        final private function fieldTypeList()
        {
            return array(
                ID_FIELD => Parse::text('Id field'),
                INT_FIELD => Parse::text('Integer field'),
                DEC_FIELD => Parse::text('Decimal field'),
                STR_FIELD => Parse::text('String field'),
                TEXT_FIELD => Parse::text('Long text field'),
                BOOL_FIELD => Parse::text('Boolean field'),
                DATE_FIELD => Parse::text('Date field'),
                TIME_FIELD => Parse::text('Time field'),
                DATE_TIME_FIELD => Parse::text('Date and time field'),
                YEAR_FIELD => Parse::text('Year field'),
                IMG_FIELD => Parse::text('image field'),
                FOREIGN_KEY_FIELD => Parse::text('Forean key field'),
            );
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