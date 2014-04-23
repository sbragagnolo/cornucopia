<?php
include_once "Verifier.php";
include_once "../session/SessionObject.php";

class Expression implements Criteria, SessionObject {
	protected $_args;

	public function __construct() {
		$this->_args = func_get_args();
	}
	/**
	 *@return Expression
	 * */
	public function pushArg ($arg) {
		$this->_args[]= $arg;
		return $this;
	}
	/**
	 *@return Expression
	 * */
	public function putArgAt($i, $arg){
		$this->_args[$i] = $arg;
		return $this;
	}
	
	public function toArray(){
		$array = array ();
		$array["Expression_args"]= $this->_args;
		return $array;
	}
	
	public function loadArray($array) {
		$this->_args = $array["Expression_args"];
		return $this; 
	}

	public function sql () {
		$sql = $this->_args [0];

		for ($i = 1; $i < count($this->_args); $i++){
			$sql = str_replace("{$i}", Verifier::acutes(Verifier::verifySelect($this->_args[$i]), $sql));
		}
		return $sql;
	}

	public function setValue ($value){
		$this->_args[] = $value;
	}
}