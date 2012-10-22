<?php
/*
* LAYER OF COMUNICATION
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
        
        /*
		* INSERTING ERRORS
		* Type: array
		* Array with the Inerts Errors.
		*/
        private $_savingErrors = array();

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
            $_savingErrors = array();
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
        
		//Funcion callback, se explica luego
		private function replaceParams($coincidencias){
			$b=current($this->params);
			next($this->params);
			return $b;
		}
		
		//Se encarga de poner los par�metros en su sitio  
		private function prepare($sql, array $params){
			for($i=0;$i<sizeof($params); $i++){
				if(is_bool($params[$i])){
					$params[$i] = $params[$i]? 1:0;
				}
				elseif(is_double($params[$i]))
					$params[$i] = str_replace(',', '.', $params[$i]);
				elseif(is_numeric($params[$i]))
					$params[$i] = $this->provider->escape($params[$i]);
				elseif(is_null($params[$i]))
					$params[$i] = "NULL";
				else
					$params[$i] = "'".$this->provider->escape($params[$i])."'";
			}

			$this->params = $params;
			$q = preg_replace_callback("/(\?)/i", array($this,"replaceParams"), $sql);

			return $q;
		}
		
		//Envia la consulta al servidor  
		private function sendQuery($q, array $params){
			$query = $this->prepare($q, $params);
			$result = $this->provider->query($query);
			if($this->provider->getErrorNo()){
				return false;
			}
			return $result;
		}
		
        /*
		* SELECT
        * Execute a query extracting only the firs column of the first file.
        *
		* @Params:
        *   $q: String. The consult with ? en the params.
        *   $params: Array. Array with value to replace ? character
        * @On error: null
        * 
        * @Cases of use: When the SQL sntenses
		*/
		final public function executeScalar($q, $params=null){
			$result = $this->sendQuery($q, $params);
			if(!is_null($result)){
				if(!is_object($result)){
					return $result;
				}else{
					$row = $this->provider->fetchArray($result);
					return $row[0];
				}
			}
			return null;
		}  

		//Ejecuta una consulta y devuelve un array con las filas  
		final public function execute($q, $params=null){
			$result = $this->sendQuery($q, $params);
			if(is_object($result)){
				$arr = array();
				while($row = $this->provider->fetchArray($result)){
					$arr[] = $row;
				}
				return $arr;
			}
			return null;
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
		* @Params: String. The name of the table to insert
		* @Return: mysqli::query() object
        * @On error: null
		*/
		final public function query($q)
		{
			return $this->sendQuery($q, array());
		}
        
        /*
		* INSERT
        * Make a SQL insert 
        * 
		* @Params: Array. The name of the table to insert and the fields with value in array key=>value.
        *   @Params example:
         *      $paramsInsert = array(
         *          'update' => array(
         *              'users' => array(
         *                  'id' => 1,
         *                  'name'=> 'jhon',
         *              ),
         *          ),
         *          'insert' => array(
         *              'Clients' => array(
         *                  'id' => 2,
         *                  'phone' => '4254-5874',
         *              ),
         *          ),
         *      ), 
        * 
		* @Return: mysqli::query() object.
        * @On error: null
		*/
        final public function save(array $params = NULL)
        {
            $toReturn = true; // If not errors, return true, else return NULL.
            $this->_savingErrors = array(); // Array to saving errors. Una row for table to save.
            
            if ($params === NULL) // If haven't parameters
            {
                $this->_savingErrors['Params'] = Parse::text('Not has set parameters'); // Set errors message on array.
                return NULL;
            }
            
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
                            echo '<br />'.$q.'<br />';
                            break;

                        case 'update':
                            if (!isset($values['set']))
                            {
                                $toReturn = NULL;
                                $this->_savingErrors[$table] = Parse::text('The SET parameters are not defined'); // Set errors message on array.
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
                            $this->_savingErrors[$action] = Parse::text('Action is not defined'); // Set errors message on array.
                    }
                    print_r($this->_savingErrors);
                    if (!$this->sendQuery($q, array())){ // If can't make the action.
                        $this->_savingErrors[$table] = Parse::text('Failed to {action}', array('action' => $action)); // Set errors message on array.
                        $toReturn = NULL;
                    }
                }
            }
            return $toReturn;
        }

      /*************/
	 /*  GETTERS  */
	/*************/
        
        /*
        * INSERTING ERRORS(GETTER))
        * Return an array with the inserting errors.
        *
        * @params: void
        * @return: Array.
        */
        final public function getSavingErrors()
        {
            return $this->_savingErrors;
        }
        
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