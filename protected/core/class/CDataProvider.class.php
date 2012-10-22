<?php
	/*
	* CLASE ABASTRACTA PARA FEDINIR EL DATAPROVIDER (MySQL, SQLServer, ORACLE, ETC).
	* Extraído de: 
	* NOTA: ver SET NAMES UTF8; para que escriba bien los caracteres especiales
	*/
	/*
	* ANSTRACT CLASS TO DEFINE DATA PROVIDER
	* CDataProvider
	* @Original Code: http://web.ontuts.com/tutoriales/creando-una-capa-de-conexion-abstracta-a-base-de-datos-con-php/
	*/
	abstract class CDataProvider
	{  
		//Guarda internamente el objeto de conexi�n
		protected $resource;
		//Se conecta seg�n los datos especificados
		public abstract function connect($host, $user, $pass, $dbname, $charset);
		//Obtiene el n�mero del error
		public abstract function getErrorNo();
		//Obtiene el texto del error
		public abstract function getError();
		//Env�a una consulta
		public abstract function query($q);
		//Convierte en array la fila actual y mueve el cursor
		public abstract function fetchArray($resource);
		//Comprueba si est� conectado
		public abstract function isConnected();
		//Escapa los par�metros para prevenir inyecci�n
		public abstract function escape($var);
		//Close database
		public abstract function closeDataBase();
	}
	
	/*
	* MySql Provider
	*/
	class CMySqlProvider extends CDataProvider  
	{
		public function connect($host, $user, $pass, $dbname, $charset = 'utf8'){
			$this->resource = new mysqli($host, $user, $pass, $dbname);
            if (!$this->resource->set_charset($charset)) {
                throw new CustomException(Parse::text('Charset {charset} unrecognized.', array('charset'=>$charset)));
            }
			return  $this->resource;
		}
		
		public function getErrorNo(){
			return mysqli_errno($this->resource);
		}
		public function getError(){
			return mysqli_error($this->resource);
		}
		public function query($q){
			return mysqli_query($this->resource,$q);
		}
		public function fetchArray($result){
			return mysqli_fetch_array($result);
		}
		public function isConnected(){
			return $this->resource->connect_error == null;
		}
		public function escape($var){
			return mysqli_real_escape_string($this->resource,$var);
		}
		
		// WARNING: No test.
		public function closeDataBase()
		{
			$this->resource->close();
		}
	}
?>