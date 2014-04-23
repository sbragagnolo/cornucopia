<?php

include_once "Access.php";
include_once "Criteria.php";
include_once "Verifier.php";
include_once "../dependency/Container.php";
include_once "../session/SessionObject.php";

/*
 *
 * new From ("Persona")->add(new Expression(" Persona.nombre like '%{1}%'", $numero))
 * */
class From implements Access, SessionObject {
	protected $_restrictions;
	protected $_from;
	protected $_columns;
	protected $_ob;

	protected $_limit;
	protected $_begin;

	
	
	public function toArray () {
		$array = array();
				
		$array["From_from"] = $this->_from;
		$array["From_columns"] = $this->_columns;
		$array["From_ob"] = $this->_ob;
		$array["From_limit"] = $this->_limit;
		$array["From_begin"] = $this->_begin;

		
		$array["From_restrictions"] = array();
		foreach ($this->_restrictions as $restriction){
			if (!isset($array["From_restrictions"][get_class($restriction)])){
				$array["From_restrictions"][get_class($restriction)] = array();
			}
			$array["From_restrictions"][get_class($restriction)][] = $restriction->toArray(); 
		}
		return $array;
	}
	
	public function loadArray($array) {
		$this->_from = $array["From_from"];
		$this->_columns = $array["From_columns"];
		$this->_ob = $array["From_ob"];
		$this->_limit = $array["From_limit"];
		$this->_begin = $array["From_begin"];

		foreach ($array["From_restrictions"] as $class => $objects){
			foreach ($objects as $subarray ){
				$restriction = new $class ();
				$restriction->loadArray($subarray);
				$this->_restrictions [] = $restriction;
			} 
		}
		return $this;
	}
	
	/**
	 *@return From
	 * */
	public function __construct($class) {
		$this->_restrictions = array();
		$this->_from = $class;
		$this->_columns = array ("*");
	}
	
	/**
	 *@return From
	 * */
	public static function From ($class) {
		return new From($class);
	}
	
	public function _accessClass() {
		return $this->_from;
	}
	/**
	 *@return From
	 * */
	public function addColumn ($col){
		if (count($this->_columns) > 1){
		}
		$this->_columns[]=$col;
		return $this;
	}
	
	/**
	 *@return From
	 * */
	public function add(Criteria $criteria) {
		$this->_restrictions[] = $criteria;
		return $this;
	}

	/**
	 *@return From
	 * */
	public function orderBy ($val) {
		$this->_ob = $val;
		return $this;
	}

	/**
	 *@return From
	 * */
	public function limit ($begin, $cantidad) {
		$this->_limit = $cantidad;
		$this->_begin = $begin;
		return $this;
	}

	public function informTotal(){
		$columns = " count(*) total";
		$from = $this->_tableFor($this->_from);
		$where = "";

		$where  = new CriteriaAND ($this->_restrictions);
		$where = $where->sql();
		
		if(strlen(ltrim(rtrim($where))) > 0 ) {
			$where = " WHERE " . $where;
		}
		
		return
		" SELECT $columns
		  FROM $from
		  $where
		 ";
		
	}
	protected function _tableFor($class){
		$repo = Container::instance("Repository");
		return $repo->getProxyPrototype($class)->getTable();
	} 
	public function sql () {
		$columns = implode(",", $this->_columns);
		$from = $this->_tableFor($this->_from);
		$where = "";

		$where  = new CriteriaAND ($this->_restrictions);
		$where = $where->sql();
		
		if(strlen(ltrim(rtrim($where))) > 0 ) {
			$where = " WHERE " . $where;
		}

		$ob = "";
		if (strlen(ltrim(rtrim($this->_ob))) > 0) {
			$ob = "ORDER BY " . $this->_ob;
		}

		/**
		 * @Todo: Sacar el limit de aca. Ponerlo en el adapter de mysql.-
		 * */
		$limit = "";
		if ($this->_limit) {
			if ($this->_begin){
				$ob = "LIMIT " . $this->_begin . ", " . $this->_limit;
			}
			else {
				$ob = "LIMIT " . $this->_limit;
			}
		}
		return
		" SELECT $columns
		  FROM $from
		  $where
		  $ob
		  $limit ";

	}
	public function __toString(){
		return $this->sql();
	} 
}

