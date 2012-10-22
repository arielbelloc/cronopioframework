<?php

/*
* FORM CLASS
* Create and validate forms
*
* @Author: Ariel Belloc
* @Contact: arielbelloc@gmail.com
*/

class CForm
{
	/*
	* Constructor mothod
	*/
	public function __construct()
	{
	
	}
	
	/*
	* CREATE FORM FROM TABLE
	* Create the CRUD from a table
	*
	* @params:
	*	$tableName: Name of the table to constrct the form.
	* 	$$id: if $id is diferente to 0 (zero) the operation is update, else the operati�n is insert.)
	* @return: Html
	*/
	public function createFormFormTable($tableName, $id = NULL, array $tableConfig = NULL)
	{
		global $db; // declare global connection
        
		$fields = $db->tableFields($tableName); //Get fields information
		echo $tableName;
		if ($fields) { // If success
			Html::startForm(); // Initialize form
			foreach ($fields as $f)
			{
				Html::fieldType($f); // Create input fields
			}
			Html::endForm(true); // Close form
		}
	}
	
	/*
	* CREATE FORM (NOT CREATED YET)
	* Create a form
	*
	* @params:
	*	$formParams: Array. The params of the form
	* @return: Html
	*/
	public function createForm()
	{
		global $db; // declare global connection
		
		$fields = $db->tableFields($tableName); //Get fields information
		echo $tableName;
		if ($fields) { // If success
			Html::startForm(); // Initialize form
			foreach ($fields as $f)
			{
				Html::fieldType($f); // Create input fields
			}
			Html::endForm(true); // Close form
		}
	}
}
?>