<?php
/*
* DEBUG CLASS
* A few methods to debug the application.
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/
class CDebug
{
    /*
	* DEBUG LIST
	* Save the params to debug
	*
	* @type: array.
	*/
    protected static $debugList = array();

    /*
	* TIME START
	* Save the time in start the process petition
	*
	* @type: array.
	*/
    protected static $timeStart = array();
    
    /*
	* TIME END
	* Save the time in end the process petition
	*
	* @type: array.
	*/
    protected static $timeEnd = array();
    
    /*
	* MEMORY START
	* Save the memory size in start the process petition
	*
	* @type: array.
	*/
    protected static $memoryStart = array();
    
    /*
	* MEMORY END
	* Save the memory size in end the process petition
	*
	* @type: array.
	*/
    protected static $memoryEnd = array();
    
    /*
	* START DEBUG
	* Set the start debug params
	*
	* @return: Void.
	*/
    public static function startDebug()
    {
        if (Config::settings()->debugEnabled)
        {
            self::$timeStart = microtime(true);
            self::$memoryStart = (integer) memory_get_usage(true);
        }
    }
            
            
    
    /*
	* ADD DEBUG PARAMETERS
	* add params to Debug List
	*
    * @params: $params: array. The params tu add in key=>value array
	* @return: Coid
	*/
    public static function addDebugParams(array $params){
        if (Config::settings()->debugEnabled)
        {
            foreach ($params as $key => $value)
            {
                if (isset(self::$debugList[$key])) {
                    if (!is_array(self::$debugList[$key])) {
                        self::$debugList[$key] = array(self::$debugList[$key]);
                    }
                    array_push(self::$debugList[$key], $value);
                }else {
                    self::$debugList[$key] = $value;
                }
            }
        }
    }
    
    /*
	* MESSAGE
	* Show a message with HTML format
	*
    * @params: $message. String
	* @return: HTML code.
	*/
    public static function showMessage($message)
    {
        if (Config::settings()->debugEnabled)
        {
            echo '<br />';
            echo '<hr />';
            echo $message;
            echo '<br />';
            echo '<hr />';
        }
    }
            
    
    /*
	* SHOW DEBUG PARAMS
	* SHOW A TABLE WITH THE PARAMS
	*
	* @return: HTML code.
	*/
    public static function showDebugParams()
    {
        if (Config::settings()->debugEnabled)
        {
            self::$timeEnd = microtime(true);
            self::$memoryEnd = (integer) memory_get_usage(true);
            echo '<br />';
            echo '<hr />';
            echo '<strong>DEBUG LIST</strong>';
            echo '<br />';
            echo '<hr />';
            self::displayArray(self::$debugList);

            $time = self::$timeEnd - self::$timeStart;
            $time = Parse::formatNumber($time, true, 3);
            $memoty = self::$memoryEnd - self::$memoryStart;

            echo Parse::text('The petition took {time} seconds and and been consumed {memory} bytes', array('time'=>$time, 'memory' => $memoty)) . '<br />';
            
            echo parse::text('Used memory: {memoryPartial} KB of {totalMemory} KB', array('memoryPartial' => round(memory_get_usage() / 1024,1), 'totalMemory' => round(memory_get_usage(1) / 1024,1) ));
        }
    }
    
            
   /*
	* RANDOM STATE
	* Return true or false with 50% probability
	*
	* @return: Bool
	*/
	public static function randomState()
	{
        if (Config::settings()->debugEnabled)
        {
            if (rand(0,100) > 50)
                return true;
            else
                return false;
        }
	}
	
	/*
	* DISPLAY ARRAY
	* Return the array with format
	*
	* @params
	*	$array: Array. The array to show.
	*	$toString: Bool. Define if return string (true) or html (false)
	*
	* @return: Html or String
	*/
	public static function displayArray($array, $toString = false)
	{
        if (Config::settings()->debugEnabled)
        {
            $toReturn = '';
            $toReturn .= '<pre>';
            $toReturn .= print_r($array, true);
            $toReturn .= '</pre>';

            if ($toString) // If $toString param is true
                return $toReturn; // return string
            else
                echo $toReturn; // return html
        }
	}
	
	/*
	* TABLE INFO
	* Return info about the fields of a table.
	*
	* @params
	*	$tableName: String or void. Define de name of the table.
	*	$toString: Bool. Define if return string (true) or html (false)
	*
	* @return: Html or string
	*/
	public static function tableInfo($tableName = NULL, $toString = false)
	{
        $db = CDbConnection::getConnection();
		
		$toReturn = '';
		
		if (is_null($tableName))
		{
			// Show all tables
			$showTablesQuery ="SHOW TABLES";
			$showTables = $db->query($showTablesQuery) or die(Parse::text('Can not execute the query '));
			while($st = $showTables->fetch_array()) {
			
				// Describle the fields by tabla
				$describeTableQuery ="DESCRIBE ".$st[0];
				$describeTable = $db->query($describeTableQuery) or die(Parse::text('Can not execute the query '));
				
				$toReturn .= '<table width="100%" class="listado_tablas">';
				$toReturn .= '<tr><th colspan="4">'.$st[0].'</th></tr>';
				
				//Show fields information.
				while($dt = $describeTable->fetch_array())
				{
					$toReturn .= '<tr>';
					$toReturn .= '<td width="55%">'.$dt['Field'].'</td>';
					$toReturn .= '<td width="25%">'.$dt['Type'].'</td>';
					$toReturn .= '<td width="10%">'.$dt['Null'].'</td>';
					$toReturn .= '<td width="10%">'.$dt['Key'].'</td>';
					$toReturn .= '</tr>';
				}
				$toReturn .= '</table>';
			}
		}else{
			$describeTableQuery ="DESCRIBE ".$tableName;
			$describeTable = $db->query($describeTableQuery) or die(Parse::text('Can not execute the query '));
			
			$toReturn .= '<table width="100%" class="listado_tablas">';
			$toReturn .= '<tr><th colspan="4">'.$tableName.'</th></tr>';
			
			//Show fields information.
			while($dt = $describeTable->fetch_array())
			{
				$toReturn .= '<tr>';
				$toReturn .= '<td width="55%">'.$dt['Field'].'</td>';
				$toReturn .= '<td width="25%">'.$dt['Type'].'</td>';
				$toReturn .= '<td width="10%">'.$dt['Null'].'</td>';
				$toReturn .= '<td width="10%">'.$dt['Key'].'</td>';
				$toReturn .= '</tr>';
			}
			$toReturn .= '</table>';
		}
	
		//If you want display the code of the type of field.
		/*		
		$table = $db->tableFields($tableName);
		foreach($table as $t)
			echo '<b>'.$t->name . ':</b> ' . $t->type . ' - ' . $t->length . '<br />';
		*/
		
		if ($toString) // If $toString param is true
			return $toReturn; // return string
		else
			echo $toReturn; // return html
	}
	
	/*
	* DEBUG REQUEST ACTION
	* return the path to debug_request.php
	*
	* @params: void.
	* @return: The path of debug_request.php with the nama of file.
	*/
	public static function debugRequestAction()
	{
        if (Config::settings()->debugEnabled)
        {		
            return Config::settings()->debugRequestPath.Config::settings()->debugRequestAction;
        }else{
            return NULL;
        }
	}
}
?>