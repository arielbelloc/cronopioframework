<?php
/*
* DB CONNECTION: LAYER OF COMUNICATION
* CDbConnection Class
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
	class CDbConnection
	{  
      /********************/
	 /*  PRIVATE PARAMS  */
	/********************/        
	    //Almacena internamente el proveedor  
		private $provider;
		//Usado para las callbacks, se explica luego  
		private $params;
		//Almacena la instancia para el Singleton  
		private static $_con;
        

	  /********************************/
	 /*  CONSTRUCTOR AND INITIALIZE  */
	/********************************/
        
		//Constructor privado  
		private function __construct($provider)
		{
			if(!class_exists($provider)){
				throw new Exception("El proveedor especificado no ha sido implentado o a�adido.");
		    }
            
			$this->provider = new $provider;
			$this->provider->connect(Config::conectionData()->host, Config::conectionData()->user, Config::conectionData()->password, Config::conectionData()->DB, Config::conectionData()->charset);
			if(!$this->provider->isConnected()){
				return NULL;
			}
		}

		//Funcion del Singleton que devuelve o crea la instancia
		final public static function getConnection($provider = NULL)
		{
			if (is_null($provider))
				$provider = Config::conectionData()->dataProvider;

			if(self::$_con){
				return self::$_con;
			}
			else{
				$class = get_called_class();
				self::$_con = new $class($provider);
				return self::$_con;
			}
		}

      /*******************/
	 /*  PUBLIC METHOD  */
	/*******************/
        
		public function getErrorNo(){
			return $this->provider->getErrorNo();
		}
        
		public function getErrorMsg(){
			return $this->provider->getError();
		}
		
        /*
		* SAVE
        * Make a SQL insert/update (Action / Table / Fields => Values)
        * 
		* @Params: Array. The name of the table to insert and the fields with value in array key=>value.
        *   @Params example:
         *      $paramsSave = array(
         *          'update' => array(
         *              'set' => array(
         *                  'users' => array(
         *                      'id' => 1,
         *                      'name'=> 'jhon',
         *                  ),
         *              ),
         *              'where' => 'id = 25 && name like '%a%',
         *          ),
         *          'insert' => array(
         *              'Clients' => array(
         *                  'id' => 2,
         *                  'phone' => '4254-5874',
         *              ),
         *          ),
         *      ), 
        * 
		* @Return: TRUE.
        * @On error: null
		*/
        final public function save(array $params = NULL)
        {
            $toReturn = true; // If not errors, return true, else return NULL.
            $savingErrors = array(); // Array to saving errors. Una row for table to save.
            $q = ''; // String variable to create the query.
            
            if (!isset($params)) // If haven't parameters
            {
                $savingErrors['Params'] = Parse::text('Not has set parameters'); // Set errors message on array.
                return NULL;
            }
            
            $params =  array_change_key_case($params, CASE_LOWER);
            
            foreach ($params as $action => $saveParams) // For each table.
            {
                foreach ($saveParams as $table => $values) // For each table.
                {
                    switch ($action)
                    {
                        case 'insert':
                            $fieldArray = array();
                            $valueArray = array();

                            foreach($values as $field => $value)
                            {
                                array_push($fieldArray, (string) $field);
                                array_push($valueArray, (string) Parse::formatOfField($value));
                            }
                            $fieldString = implode(', ', $fieldArray);
                            $valueString = implode(', ', $valueArray);

                            $q = 'INSERT INTO ' . $table . ' (' . $fieldString . ') VALUES (' . $valueString . ')'; // Set a query.
                            break;

                        case 'update':
                            if (!isset($values['set']))
                            {
                                $toReturn = NULL;
                                $savingErrors[$table] = Parse::text('The SET parameters are not defined'); // Set errors message on array.
                                continue;
                            }
                            $setArray = array();
                            foreach($values['set'] as $field => $value){
                                array_push($setArray, (string) $field . ' = ' . Parse::formatOfField($value));
                            }
                            $setParams = implode(', ', $setArray);
                            
                            $where = '';
                            if (isset($values['where'])){
                                $where = ' WHERE ' . $values['where'];
                            }
                            $q = 'UPDATE ' . $table . ' SET ' . $setParams . $where; // Set a query.
                            break;
                            
                        default:
                            $toReturn = NULL;
                            $savingErrors[$action] = Parse::text('Action is not defined'); // Set errors message on array.
                    }
                    
                    if ($this->query($q)) {
                        Debug::addDebugParams(array('Insert' => $q));
                    }else{ // If can't make the action.
                        $savingErrors[$table] = array(Parse::text('Failed to {action}', array('action' => $action))=>$this->getErrorNo() . ': ' .$this->getErrorMsg()); // Set errors message on array.
                        $toReturn = NULL;
                    }
                }
            }
            if (count($savingErrors) > 0) {
                Debug::addDebugParams(array('Save failed' => $savingErrors));
            }
            return $toReturn;
        }
        
        final public function delete($table = NULL, $where = NULL)
        {
            if (!isset($table)){
                throw new CustomException('Not was set a table name in the delete query');
            }
            
            if (!isset($where)){
                throw new CustomException('Not was set a where string in the delete query');
            }
            
            $query = 'DELETE FROM ' . $table . ' WHERE ' . $where;
            $this->query($query);
        }
        
        
        
		/*
		* TABLE FIELDS METHOD
		* @Return array with fields info
		* 	name: The name of the column
		* 	orgname: Original column name if an alias was specified
		* 	table: The name of the table this field belongs to (if not calculated)
		* 	orgtable: Original table name if an alias was specified
		* 	max_length: The maximum width of the field for the result set.
		* 	length: The width of the field, as specified in the table definition.
		* 	charsetnr: The character set number for the field.
		* 	flags: An integer representing the bit-flags for the field.
		* 	type: The data type used for this field
		* 	decimals: The number of decimals used (for integer fields)
		*
		* @params:
		*	$table: String. The name of the table to parse.
		* 	$fields: Array (Optional). The fields to parse.
        * 
        * @On error: null
		*/
		final public function tableFields($table, array $fields = NULL){
			
			$q = "SELECT ";
			if ($fields === NULL)
            {
				$q .= '*';
            }
			else
            {
				$q .= implode(',',$fields);
            }
            
			$q .= ' FROM ' . $table . ' WHERE FALSE';
			
			$result = $this->sendQuery($q, array());
			if ($result)
				return $result->fetch_fields();
			else
				return NULL;
		}
		
        /*
		* SELECT
        * Make a SQL sentence
        *
		* @Params: String. The query to make
		* @Return: instance of mysqli::query()
        * @On error: null
		*/
		final public function query($query)
		{
            if (!isset($query) || empty($query)) {
                return NULL;
            }
            
            $result = $this->provider->query($query);
			if($this->getErrorNo()){
				return NULL;
			}
			return $result;
		}
        
        /*
		* ROW BY QUERY
        * Make a SQL sentence
        *
		* @Params: String. The query to make
		* @Return: instance of mysqli::query()
        * @On error: null
		*/
		final public function rowByQuery($query)
		{
            if (!isset($query) || empty($query)) {
                return NULL;
            }
            
            $result = $this->provider->query($query);
			if($this->getErrorNo()){
				return NULL;
			}
			return $result->fetch_object();
		}
        
        /*
		* INSERT
        * Make a SQL sentence
        *
		* @Params: String. The query to make
		* @Return: instance of mysqli::query()
        * @On error: null
		*/
		final public function insert(array $params = NULL)
		{
            if (!isset($params) || count($params)<1) {
                return NULL;
            }
            
            $params = array('insert' => $params);
            
			if(!$this->save($params)){
				return NULL;
			}else{
                return TRUE;
            }
		}

        /*
		* UPDATE
        * Make a SQL sentence
        *
		* @Params: String. The query to make
		* @Return: instance of mysqli::query()
        * @On error: null
		*/
		final public function update(array $params = NULL, $where = NULL)
		{
            if (!isset($params) || count($params)<1) {
                return NULL;
            }
            
            if (!isset($where)) {
                return NULL;
            }
            
            $paramsUpdate = array();
            $paramsUpdate['update'] = array();
            foreach ($params as $table => $value)
            {
                $paramsUpdate['update'][$table]['set'] = $value;
                $paramsUpdate['update'][$table]['where'] = $where;
            }
			if(!$this->save($paramsUpdate)){
				return NULL;
			}else{
                return TRUE;
            }
		}
        
      /*************/
	 /*  GETTERS  */
	/*************/
        
      /****************/
	 /*  DESTRUCTOR  */
	/****************/
		
        /*
		* DESCTRUCT
        * Close the database connection
        * 
		* @return: Void
		* @params: Void
		*/
        
		// WARNING: No test.
		final public function __destruct()
		{
			if ($this->provider->isConnected())
				$this->provider->closeDataBase();
		}
	}
?>