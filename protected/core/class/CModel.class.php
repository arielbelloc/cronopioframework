<?php
	/*
	* MODEL CLASS
	* Data Access Layer
	*
	* @Author: Ariel Belloc
	* @Contact: arielbelloc@gmail.com
	*/

	class CModel extends CBaseClassSingleton
	{
	
	
  	  /****************/
	 /*  PROPERTIES  */
	/****************/
        /*
		* DATABASE
		* Type: Instance of CDbConnection
		* Create a instance of CDbConnection
		*/
		protected $_db;
        
		/*
		* MODEL NAME
		* Type: string
		* Define the name of the model.
		*/
		protected $_modelName;
        
        /*
		* TABLE NAME
		* Type: string
		* Define the name of the table.
		*/
		protected $_tableName;
		
		/*
		* IS RELATED?
		* Type: Boolean
		* Define if the model is related with other model
		*/
		protected $_isRelated;
		
		/*
		* DESCRIPCTION FIELD
		* Type: String or Array
		* Define the field that describe the model.
		* Example:
		*	Model: Product / $_descriptionField = 'name'
		*	Model: Employed / $_descriptionField = array('name', 'last_name')
		*/
		protected $_descriptionField;
		
		/*
		* DEFINITIVE ERASED
		* Type: Boolean
		* Define if the model chengue status o registries or erase it.
		*/
		protected $_definitiveErased;
	
		
	  /********************************/
	 /*  CONSTRUCTOR AND INITIALIZE  */
	/********************************/
		
		/*
		* CONSTRUCTOR
		* Singleton Design Pattern
		*/
		final protected function __construct()
		{
			// Define the default value of propierties.
            $this->_db = CDbConnection::getConnection();
            $this->_modelName = get_called_class();
			$this->_definitiveErased = false;
			$this->_descriptionField = 'description';
			$this->_isRelated = false;
			$this->init(); // Call the constructor of the instances.
		}

        /*
		* MODEL STATIC METHOD
		* Create an instance of model (Singleton Design Pattern).
		*
		* @params: Void 
		* @return: Instance of model
		*/
		public static function model($modelName = NULL)
		{
			return self::getInstance($modelName);
		}
		
		/*
		* INIT
		* Use same a constructor in a model instance.
		*
		* @params: Void 
		* @return: Void
		*/
		public function init()
		{
		}

      /********************/
	 /*  PRIVATE METHOD  */
	/********************/
        
        /*
		* MAKE SELECT QUERY
		* Make a select query from params of method: fiend, fiendFirst, fiendById, etc.
		*
		* @params:
         *  $params = the params to create the query.
		* @return: Intance of MySQLi. The result of the query.
		*/
        private function makeSelectQuery ($params = NULL){
            if (isset($params)) {
                $params =  array_change_key_case($params, CASE_LOWER);

                $query = 'SELECT ';

                if (isset($params['select']))
                {
                    if (is_array($params['select'])) {
                        $query .= implode(', ', $params['select']);
                    }else{
                        $query .= $params['select'];
                    }
                }else{
                    $query .= ' *';
                }

                if (isset($params['from'])) {
                    $query .= ' ' . $params['from'];
                }else{
                    $query .= ' FROM ' . $this->_tableName;
                }

                if (isset($params['where'])) {
                    $query .= ' WHERE ' . $params['where'];
                }

                $order = ' ORDER BY ';
                if (isset($params['order']))
                {
                    $query .= $order;
                    if (is_array($params['order'])) {
                        $query .= implode(', ', $params['order']);
                    }else{
                        $query .= $params['order'];
                    }
                }
                else
                {
                    if (isset($this->_descriptionField)) {
                        $query .= $order;
                        $query .= $this->_descriptionField;
                    }
                }
                unset($order);

                if (isset($params['limit']))
                {
                    $query .= ' LIMIT ' . $params['limit'];
                }
                else
                {
                    if (isset(Config::settings()->rowPerPage)) {
                        $query .= ' LIMIT ' . Config::settings()->pagination;
                    }
                }

            }else{
                $query = 'SELECT * FROM ' . $this->_tableName;
            }

            if ($result = $this->_db->query($query)) {
                Debug::addDebugParams(array('Query' => $query));
                return $result;
            }else{
                Debug::addDebugParams(array('Query failed' => array('Params' => $params, 'Query' => $query, 'Error: ' => $this->_db->getErrorMsg() .' (' . $this->_db->getErrorNo() . ')')));
                return NULL;
            }
        }

        
      /*******************/
	 /*  PUBLIC METHOD  */
	/*******************/
        public function modelInfo()
		{
			return array(
				'fields' => array(
					'id' => array(
						'type' => ID_FIELD, // Types: ID_FIELD, INT_FIELD, DEC_FIELD, STR_FIELD, TEXT_FIELD, BOOL_FIELD, DATE_FIELD, TIME_FIELD, DATE_TIME_FIELD, YEAR_FIELD, IMG_FIELD, FOREAN_KEY_FIELD
					),
					'name' => array(
						'type' => 'string',
					),
				),
                // The field that describe better the model.
                'descriptionField' => 'name',
			);
			
			/*
			* Hints: 
			*	ajaxValidaro (bool)
			*	
			*/
		}
        
        /*
        * LIST BOX
        * Create a combobox with the id field and description field.
        *
        * @params: Void
        * @return: String
        */
        public function listBox($descriptionField = NULL)
        {
            if (!isset($descriptionField)) {
                $descriptionField = $this->_descriptionField;
            }
            
            //$options = $this->find(array('select' => array('id', $descriptionField)));
            $options = $this->find();
            $params = array();
            foreach ($options as $row => $fields)
            {
                $params[$fields['id']] = $fields['descripcion'];
            }
            Html::createCmb($params);
        }
		

		/*
		* FIND BY IP
		* Search e 
		*
		* @params: String. 
		* @return: Instance of model
		*/
		final public function findById($id, array $fields = null)
		{
            $params = array(
                'select' => $fields,
                'limit' => 1,
            );
            
            if ($result = $this->makeSelectQuery($params)) {
                return $result->fetch_object();
            }else{
                return NULL;
            }
		}
		
		final public function find(array $params = null)
		{
            if ($result = $this->makeSelectQuery($params)) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }else{
                return NULL;
            }
		}
		
        final public function findFirst(array $params = null)
		{
            if ($result = $this->makeSelectQuery($params)) {
                return $result->fetch_object();
            }else{
                return NULL;
            }
		}
        
        
        /*
		* INSERT
        * Make a SQL insert (Table: Fields => Values)
         * VIEW SAVE METHOD
        * 
        */
        final public function insert(array $params = NULL) {
            $params = array('insert' => $params);
            $this->save($params);
        }
        
        /*
		* UPDATE
        * Make a SQL update (Table: SET (Fields => Values) / WHERE (string))
         * VIEW SAVE METHOD
        * 
        */
        final public function update(array $params = NULL) {
            $params = array('update' => $params);
            $this->save($params);
        }
        
        /*
		* SAVE
        * @On error: null
		*/
        final public function save()
        {
            $toReturn = true; // If not errors, return true, else return NULL.
            $q = ''; // String variable to create the query.
            
            $id = Parse::postInt('id');
            
            if (isset($id))
            {
                $action = 'UPDATE';
                $setArray = array();
                foreach($_POST as $field => $value){
                    if ($field == 'id') {
                        continue;
                    }
                    array_push($setArray, (string) $field . ' = ' . Parse::formatOfField($value));
                }
                $setParams = implode(', ', $setArray);

                $where = ' WHERE id = ' . $id;
                
                $q = $action . ' ' . $this->_tableName . ' SET ' . $setParams . $where; // Set a query.
            }
            else
            {
                $action = 'INSERT';
                $fieldArray = array();
                $valueArray = array();

                foreach($_POST as $field => $value)
                {
                    array_push($fieldArray, (string) $field);
                    array_push($valueArray, (string) Parse::formatOfField($value));
                }
                $fieldString = implode(', ', $fieldArray);
                $valueString = implode(', ', $valueArray);

                $q = $action . ' INTO ' . $this->_tableName . ' (' . $fieldString . ') VALUES (' . $valueString . ')'; // Set a query.
            }
                    
            if ($this->_db->query($q)) {
                Debug::addDebugParams(array('Insert' => $q));
            }else{ // If can't make the action.
                
                Debug::addDebugParams(array(Parse::text('Failed to {action}', array('action' => $action))=>array('Query ' => $q, 'Error' => $this->_db->getErrorNo() . ': ' .$this->_db->getErrorMsg())));
                $toReturn = NULL;
            }
            
            return $toReturn;
        }
        
        
      /******************/
	 /*  DECLARATIONS  */
	/******************/
        
		public function behaviors(int $action)
		{
			
		}
    }
?>