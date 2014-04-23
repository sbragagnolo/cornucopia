<?php

include_once "../exceptions/Program.php";
include_once "../collection/Collection.php";
include_once "Connection.php";

class MySql implements Connection {
	protected $_server = "localhost";
	protected $_port= "3306";
	protected $_username;
	protected $_password;
	protected $_dbname;

	protected $_conn;

	/***
	 * @return MySql 
	 **/
	public function useServer($server){
		$this->_server = $server;
		return $this;
	}
	/***
	 * @return MySql 
	 **/
	public function usePort($port){
		$this->_port = $port;
		return $this;
	}
	/***
	 * @return MySql 
	 **/
	public function useUsername($username){
		$this->_username = $username;
		return $this;
	}
	/***
	 * @return MySql 
	 **/
	
	public function usePassword($password){
		$this->_password = $password;
		return $this;
	}

	/***
	 * @return MySql 
	 **/
	public function useDBName($dbname){
		$this->_dbname = $dbname;
		return $this;
	}
	
	protected function _connection() {
		if (!$this->_conn){
			$this->_connect();
		}
		return $this->_conn;
	}
	
	protected function _connect () {
		$this->_conn = mysql_connect($this->_server, $this->_username, $this->_password);
		if (!$this->_conn){
			throw new Program("No se pudo conectar a la base de datos");
		}
		if (!mysql_select_db($this->_dbname)){
			throw new Program("No se pudo elegir la base de datos");
		}
	}
	
	public function connect () {
		/*
		 * Este connect es para la interface connection. pero esta version es lazy, asi que aca no hace nada.
		 * */
	}
	
	public function close (){
		if($this->_conn){
			mysql_close($this->_conn);
			$this->_conn = false;
		}
	}

	/***
	 * @return MySql 
	 **/
	public function begin(){
		$this->execute(" BEGIN TRANSACTION; ");
		return $this;
	}
	/***
	 * @return MySql 
	 **/
	public function rollback() {
		$this->execute(" ROLLBACK TRANSACTION; ");
		return $this;
	}
	/***
	 * @return MySql 
	 **/
	public function commit(){
		$this->execute(" COMMIT TRANSACTION; ");
		return $this;
	}

	protected function _prepareStatement ($sql){
		return $sql;
	}
	public function describe($table) {
		return $this->query(" DESC $table ");
	}
	public function query($query,$limit = ''){
		$result = mysql_query($this->_prepareStatement($query), $this->_connection());
		if (! $result ){
			throw new Program("Error al consultar base de datos $query");
		}
		$retorno = new Collection();
		while ($value = mysql_fetch_assoc($result)){
			$retorno->add(
				$value
			);
		}
		return $retorno;
	}

	/***
	 * @return MySql 
	 **/
	public function execute($sql){
		$result = mysql_query($sql, $this->_connection());
		if (! $result ){
			throw new Program("Error al ejecutar en base de datos <br/> $sql <br/>");
		}
		return $this;
	}

	public function _init(){}

	public function _depose(){
		$this->close();
	}
	
	public function getLastId() {
		return mysql_insert_id($this->_connection());
	}
}