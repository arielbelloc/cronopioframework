<?php

/*
* PARSE CLASS
* Analize, compare and show diferent values.
*
* All method are statics
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/

class CParse
{

 	  /********************/
	 /*  STATICS METHOD  */
	/********************/

    /*
	* TEXT
	* Translated text with the language selected.
	*
	* @params:
     *  $parseText: The text to translate.
     *  $params: Array. The variables from the text.
     * 
     *  EXAMPLE:
     *      Parse::text('hi {name}, how are you?', array('name' => $name));
     * 
     *  NOTE:
     *      To create/modify the array with the translated text, use the texts folder in the base path of framework, and then add the language in the $language array in config/declarations.inc.php
    * 
	* @return: the translated text with the language selected.
    * @In error case: return the original text.
	*/
	final static public function text($parseText = NULL, array $params = NULL)
	{
		if (is_null($parseText)) {
			return NULL;
        }
        
        $translate = Params::texts($parseText);
        
        if (isset($translate)){  // If exists translate
            $parseText = $translate; // Use the translate string.
        }

        if (isset($params))
        {
            foreach ($params as $var => $value)
            {
                $parseText = str_replace('{'.$var.'}', $value, $parseText);
            }
        }
        
		return htmlentities($parseText);
	}

    
    /*
	* CAPTION
	* Translated text with the language selected.
	*
	* @params:
     *  $parseText: The text to translate.
     *  $params: Array. The variables from the text.
     * 
     *  EXAMPLE:
     *      Parse::text('hi {name}, how are you?', array('name' => $name));
     * 
     *  NOTE:
     *      To create/modify the array with the translated text, use the texts folder in the base path of framework, and then add the language in the $language array in config/declarations.inc.php
    * 
	* @return: the translated text with the language selected.
    * @In error case: return the original text.
	*/
	static public function caption($parseCaption = NULL, array $params = NULL, $Translate = TRUE)
	{
        global $captions;
        
		if (!isset($parseCaption))
			return NULL;
        
        if (!isset($captions[$parseCaption])) {
            return NULL;
        }
        
        $parseCaption = trim($captions[$parseCaption]);
        
        if ($Translate) {
            return htmlentities(self::text($parseCaption, $params));
        }else{
            $parseCaption = self::replaceTextParams($parseCaption, $params);
            return htmlentities($parseCaption);
        }
	}
    
    /*
	* REPLACE TEXT PARAMS
	* Convert a text parameter in safe parameter.
	*
	* @params:
     *  $parseText: The text to parse.
     * 
    * 
	* @return: the safe text
    * @In error case: return NULL.
	*/
    static public function replaceTextParams($text = NULL, array $params = NULL)
    {
        if (isset($params) && isset($text) )
        {
            foreach ($params as $var => $value)
            {
                $text = str_replace('{'.$var.'}', $value, $text);
            }
        }else{
            return $text;
        }
        
        return $text;
    }
    
    /*
	* POST TEXT
	* Convert a text parameter in safe parameter.
	*
	* @params:
     *  $parseText: The text to parse.
     * 
    * 
	* @return: the safe text
    * @In error case: return NULL.
	*/
    static public function postText($param = NULL)
	{
        if (isset($_POST[$param]) && !empty($_POST[$param]))
        {
            $_POST[$param] = htmlentities($_POST[$param], ENT_QUOTES);
            return $_POST[$param];
        }
        return NULL;
    }
    
    /*
	* POST INT
	* Convert a integer parameter in safe parameter.
	*
	* @params:
     *  $parseText: The integer to parse.
     * 
    * 
	* @return: the safe integer
    * @In error case: return NULL.
	*/
    static public function postInt($param = NULL)
	{
        if (isset($_POST[$param]) && is_numeric($_POST[$param]))
        {
            $_POST[$param] = (integer) $_POST[$param];
            return $_POST[$param];
        }
        return NULL;
    }
    
    
    /*
	* SAFE TEXT
	* Convert the text in a safe text.
	*
	* @params:
     *  $parseText: The text to parse.
     * 
    * 
	* @return: the safe text
    * @In error case: return NULL.
	*/
    static public function safeText($param = NULL)
	{
        if (isset($param) && !empty($param))
        {
            $param = htmlentities($param, ENT_QUOTES);
            return $param;
        }
        return NULL;
    }
    
    /*
	* SAFE INT
	* Convert the integer in a safe integer.
	*
	* @params:
     *  $parseText: The integer to parse.
     * 
    * 
	* @return: the safe integer
    * @In error case: return NULL.
	*/
    static public function safeInt($param = NULL)
	{
        if (isset($param) && is_numeric($param))
        {
            $param = (integer) $param;
            return $param;
        }
        return NULL;
    }
	
    /*
	* PATH
	* Convert the URL to path
	*
	* @params:
     *  $path: The URL to convert.
    * 
	* @return: the path.
	*/
	static public function path($path)
	{
        $path = str_replace('\\', DS, $path);
		return str_replace('/', DS, $path);
	}
	
    /*
	* URL
	* Convert the path to URL
	*
	* @params:
     *  $url: The path to convert.
    * 
	* @return: the URL.
	*/
	static public function url($url)
	{
        $url = str_replace('\\', '/', $url);
		return str_replace(DS, '/', $url);
	}

    
    /*
	* CLASS NAME ENCODE
	* Encode the name of table field (with _) with the class name format (CamelCase)
	*
	* @params:
     *  $tableName: String. The name of the field in the table.
     *  $firstLowerCase: Boolean. Define if the first letter is lowercase.
    * 
	* @return: The class name encoded.
	*/
	static public function classNameEncode($tableName, $firstLowerCase = false)
	{
		$tempClassName = explode('_', $tableName);
		$cont = 0;
		$className = '';
		foreach ($tempClassName as $tcn)
		{
			if ($cont == 0 && $firstLowerCase = false)
			{
				$className .= strtolower($tcn);
				continue;
			}

			$className .= ucfirst($tcn);
		}
		
		return $className;
	}
    
    /*
	* CLASS FROM FOREIGN KEY
	* Encode the name of class to foreign key table field
	*
	* @Params:
     *  $foreignKey: String. The name of the foreign key field.
     *  $toString: Boolean. Define if return a string to assign a variable.
     * 
     * @Example:
     *      users_permissions_id : return UsersPermissions
    * 
	* @return: The class name encoded.
	*/
    static public function classFromForeignKey($foreignKey, $toString = true)
    {
        $toReturn = substr($foreignKey, 0, strlen($foreignKey) -3);
        $toReturn = Parse::classNameEncode($toReturn);
        if ($toString)
        {
            return $toReturn;
        }
        else
        {
            echo $toReturn;
            return;
        }
    }

    /*
	* IS EMAIL
	* check if email
	*
	* @Params:
     *  $email: String. The string to parse.
    * 
	* @return: Boolean. True if is email and false if not is email.
    * @In error case: Return NULL.
	*/
    static public function isEmail($email = NULL)
    {
        if (is_null($email))
        {
            if (isset($_REQUEST['email'])) {
                $email = $_REQUEST['email'];
            }else{
                return NULL;
            }
        }
        if(preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/',$email)) {
            return true;
        }else{
            return false;
        }
    }
    
    
    /*
	* ARRAY TO ATTRIBUTES
	* Create a string with html attributes from an array key => value.
	*
	* @Params:
     *  $attributes: Array. The array with $attributes => $values.
    * 
	* @return: String.
    * @In error case: Return NULL.
	*/
    static public function arrayToAttributes(array $attributes = NULL, $fieldType = NULL)
    {
        if (!isset($attributes)) {
            return NULL;
        }
        
        if (isset($fieldType)) {
            if (isset($attributes['class'])){
                $attributes['class'] .= ' ' . Html::typeFieldParams($fieldType, 'htmlClass');
            }else{
                $attributes['class'] = Html::typeFieldParams($fieldType, 'htmlClass');
            }
        }
        
        $toReturn = '';
        foreach ($attributes as $att => $value)
        {
            $toReturn .= $att . ' = "' . $value . '" ';
        }
        
        return $toReturn;
    }
    
    /*
	* FORMAT FIELD
	* return a field with format to write in database
	*
	* @params:
     *  $field: mixed, the field value.
     *  $type: Integer. The type of field (view constant for the values). some types: ID_FIELD, INT_FIELD, DEC_FIELD, STR_FIELD, TEXT_FIELD, BOOL_FIELD, DATE_FIELD, TIME_FIELD, DATE_TIME_FIELD, YEAR_FIELD, IMG_FIELD, FOREIGN_KEY_FIELD.
    * 
	* @return: string The field with format.
	*/
    static public function formatOfField($field, $type = NULL)
    {
        if (is_null($type)) {            
            if (is_bool($field)) {
                $type = BOOL_FIELD;
            } elseif (is_string($field)) { // if is string
                $type = STR_FIELD;
            } elseif (is_numeric(self::formatNumber($field, FALSE))) {
                $type = DEC_FIELD;
                if (is_integer($field)) { // if is integer
                    $type = INT_FIELD;
                }
            }
        }

        switch ($type)
        {
            case NULL:
                return NULL;
                break;
            
            case ID_FIELD:
            case INT_FIELD:
            case YEAR_FIELD:
            case FOREIGN_KEY_FIELD:
                return (string) $field;
                break;
            
            case DEC_FIELD:
                $field = '"' . self::formatNumber($field, false) . '"';
                break;
            
            case STR_FIELD:
            case TEXT_FIELD:
            case IMG_FIELD:
                if ($type == IMG_FIELD) { // If is image field
                    $field = self::path($field); // Parse the path.
                }
                
                $field = addslashes($field); // Escape the special characters
                return '"'.$field.'"'; // add the quotes.
                break;
                
            case BOOL_FIELD:
                if ($field) {
                    return Config::settings()->trueValueToWrite;
                }else{
                    return Config::settings()->falseValueToWrite;
                }
                break;
            
            case DATE_TIME_FIELD:
                echo date(Config::settings()->dateTimeFormat);
                break;
            
            case DATE_FIELD:
                echo date(Config::settings()->dateFormat);
                break;
            
            case TIME_FIELD:
                echo date(Config::settings()->timeFormat);
                break;
                
            default :
                break;
        }
        
        
        if (is_double($field)) { // if is integer
            return self::formatNumber($field);
        }
        
        if (is_array($field)) {
            
        }
    }

    /*
	* FORMAT NUMBER
	* return a number with format
	*
	* @params:
     *  $numbrer: Integer, Decimal or Double. The number tu format
     *  $toShow: Booblean. Format number to show or to save.
     *  $decimals: Integer. number of decimals.
     *  $decimalSeparator: String. Character to decimals separator.
     *  $thousandSeparator: String. Character to thousand separator.
    * 
	* @return: string The number with format.
	*/
    static public function formatNumber($number, $toShow = true, $decimals = NULL, $decimalSeparator = NULL, $thousandSeparator = NULL)
    {
        if (is_integer($number)) { // If is integer.
            return $number;
        }
        $number = trim($number); // Erased the blankspaces.
        
        $contComa = count_substr($number, ','); // Number of (,)
        if ($contComa > 1) { // If is the number of (,) biggest that one
            $number = str_replace(',', '', $number); // Remove (,)
        }
        
        $contPoint = count_substr($number, '.'); // Number of point
        if ($contPoint > 1) { // If is the number of points biggest that one
            $number = str_replace('.', '', $number); // Remove point
        }
        if ((count_substr($number, ',') + count_substr($number, '.')) > 1 ) { // if are (,) and (.)
            if (strpos($number, ',') >= strpos($number, '.')){
                $number = str_replace('.', '', $number); // Remove point
            }else{
                $number = str_replace(',', '', $number); // Remove (,)
            }
        }

        $number =  str_replace(',', '.', $number); // Replace (,) by (.)
        
        if (!is_numeric($number)) {
            return FALSE;
        };

        if (is_null($decimals)){ // If not exists $decimals parameter
            $decimals = (int) Config::settings()->decimals; // Use default settings
        }
        if (is_null($decimalSeparator)){ // If not exists $decimalSeparator parameter
            $decimalSeparator = Config::settings()->decimalSeparatorToShow; // Use default settings
        }
        if (is_null($thousandSeparator)){ // If not exists $thousandSeparator parameter
            $thousandSeparator = Config::settings()->thousandSeparator; // Use default settings
        }

        if (($number * pow(10 , $decimals + 1) % 10 ) == 4){  //if next not significant digit is 4
            $number -= pow(10 , -($decimals+1));
        }

        if (!$toShow) { // If is to save.
            $thousandSeparator = ''; // No set thousand separator.
            $decimalSeparator = Config::settings()->decimalSeparatorToWrite; // Use default settings
        }

        return (string) number_format((string) $number, (int) $decimals, (string) $decimalSeparator, (string) $thousandSeparator);
    }
    
    final public static function fieldType(stdClass $fieldsInfo)
    {
        /* IF IS ID FIELD */
        if ($fieldsInfo->name == 'id') {
            return ID_FIELD;
        }


        /* IF IS FOREIGN KEY */
        if (strpos($fieldsInfo->name, 'id')) { // If exists the string 'id' into the name of fields, is a foreing key (the id field is analyzed before that).
            return FOREIGN_KEY_FIELD;
        }

        /* IF IS BOOLEAN */
        if ($fieldsInfo->type == 1 && $fieldsInfo->length == 1)
        {
            return BOOL_FIELD;
        }

        $toReturn;
        /* TYPES OF DATA */
        switch ($fieldsInfo->type)
        {
        /* STRING */
            case 253: // Varchar
            case 254: // Char
                    $toReturn = STR_FIELD;
                    break;
        /* TEXT */
            case 252: // Tinytext, Text, Mediumtext, Longtext, Tinyblob, Blob, Mediumblob, Longblob.
                    $toReturn = TEXT_FIELD;
                    break;
        /* INTEGER */
            case 1: // Tinyint
            case 2: // Smallint
            case 3: // Int
            case 8: // Bigint
            case 9: // Mediumint
                    $toReturn = INT_FIELD;
                    break;
        /* INTEGER */
            case 4: // Float
            case 5: // Double / Real
            case 246: // Decimal
                    $toReturn = DEC_FIELD;
                    break;

        /* DATE TIME */
            case 12: // Datetime
                $toReturn = DATE_TIME_FIELD;
                break;

        /* DATE */
            case 10: // Date
                $toReturn = DATE_FIELD;
                break;
        /* YEAR */
            case 13: // Year
                $toReturn = YEAR_FIELD;
                break;
        /* TIME */
            case 11: // Time
                $toReturn = TIME_FIELD;
                break;

            default:
                Debug::addDebugParams(array(Parse::text('Unrecognized data type. Type of data (code): {type}', array('name'=>$fieldsInfo->name, 'type'=>$fieldsInfo->type))));
                $toReturn = NULL;
                break;
        }

        return $toReturn;
    }

    final public static function fieldTypeConst($fieldType)
    {
        switch ($fieldType)
        {
            case ID_FIELD:
                return 'ID_FIELD';
                break;
            case INT_FIELD:
                return 'INT_FIELD';
                break;
            case DEC_FIELD;
                return 'DEC_FIELD';
                break;
            case STR_FIELD:
                return 'STR_FIELD';
                break;
            case TEXT_FIELD:
                return 'TEXT_FIELD';
                break;
            case BOOL_FIELD:
                return 'BOOL_FIELD';
                break;
            case DATE_FIELD:
                return 'DATE_FIELD';
                break;
            case TIME_FIELD:
                return 'TIME_FIELD';
                break;
            case DATE_TIME_FIELD:
                return 'DATE_TIME_FIELD';
                break;
            case YEAR_FIELD:
                return 'YEAR_FIELD';
                break;
            case IMG_FIELD:
                return 'IMG_FIELD';
                break;
            case FOREIGN_KEY_FIELD:
                return 'FOREIGN_KEY_FIELD';
                break;
            default :
                return NULL;
                break;
        }
    }
    
    final public static function getQueryString(array $params = NULL)
    {
        $toReturn = '?';
        Debug::addDebugParams(array($_SERVER['QUERY_STRING']));
        if (!empty($_SERVER['QUERY_STRING'])) {
            $toReturn .= $_SERVER['QUERY_STRING'];
        }else{
            return '';
        }
        if (isset($params) && count($params) > 0) {
            $params = implode('&', $params);
            $params = '&' . $params;
        }else{
            $params = '';
        }
        
        
        return $toReturn;
    }
}
?>