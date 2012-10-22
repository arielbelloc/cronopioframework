<?php
  /*************/
 /*  MODULES  */
/*************/
    define('ADMIN_MODULE', 0); // KEY FOR THE ADMIN MODULE
    define('PUBLIC_MODULE', 1); // KEY FOR THE PUBLIC MODULE
    
  /********************/
 /*  TYPE OF FIELDS  */
/********************/
    // ID_FIELD, INT_FIELD, DEC_FIELD, STR_FIELD, TEXT_FIELD, BOOL_FIELD, DATE_FIELD, TIME_FIELD, DATE_TIME_FIELD, YEAR_FIELD, IMG_FIELD, FOREAN_KEY_FIELD
    
	define('ID_FIELD', 1); // ID FIELD
	define('INT_FIELD', 2); // INTEGER FIELD
	define('DEC_FIELD', 3); // DECIMAL, FLOAT AND DOUBLE FIELD
	define('STR_FIELD', 4); // STRING FIELD
	define('TEXT_FIELD', 5); // TEXT FIELD
	define('BOOL_FIELD', 6); // BOOLEAN FIELD
	define('DATE_FIELD', 7); // DATE FIELD
	define('TIME_FIELD', 8); // TIME FIELD
	define('DATE_TIME_FIELD', 9); // DATE AND TIME FIELD
	define('YEAR_FIELD', 10); // YEAR FIELD
	define('IMG_FIELD', 11); // IMAGE FIELD
	define('FOREIGN_KEY_FIELD', 12); // FOREIGN KEY FIELD

  /********************/
 /*  TYPE OF INPUTS  */
/********************/
    define('CMB_INPUT', 13); // COMBOBOX INPUT
    define('HIDDEN_INPUT', 14); // COMBOBOX INPUT
    
  /***************/
 /*  BEHAVIORS  */
/***************/
	define('AFTER_SAVE', 1);
	define('BEFORE_SAVE', 2);
	define('AFTER_UPDATE', 3);
	define('BEFORE_UPDATE', 4);
	define('AFTER_DELETE', 5);
	define('BEFORE_DELETE', 6);
    
  /**********/
 /*  HTML  */
/**********/
	define('BR', '<br />');
    define('HR', '<hr />');
?>