<?php

include_once "Criteria.php";
include_once "Verifier.php";
include_once "../session/SessionObject.php";

class Like implements Criteria, SessionObject {
	protected $_field;
	protected $_value;
	protected $_comodin;
	
	
	public function __construct($field, $value, $comodin = "BEGIN_WITH"){
		$this->_field = $field;
		$this->_value = $value;
		$this->_comodin = $comodin;
	}
	
	
	public function toArray(){
		$array = array ();
		$array["Like_field"]= $this->_field;
		$array["Like_value"]= $this->_value;
		$array["Like_comodin"]= $this->_comodin;
		return $array;
	}
	
	public function loadArray($array) {
		$this->_field = $array["Like_field"];
		$this->_value = $array["Like_value"];
		$this->_comodin = $array["Like_comodin"];
		
		return $this; 
	}
	
	public static function LikeBeginsWith($field, $value){
		return new Like($field, $value, "BEGIN_WITH");
	}
	public static function LikeEndWith($field, $value){
		return new Like($field, $value, "END_WITH");
	}
	public static function LikeContains($field, $value){
		return new Like($field, $value, "CONTAINS");
	}
	public function setValue ($value){
		$this->_value = $value;	
	}
	public function sql () {
		
		$value = Verifier::verifySelect($this->_value);
		
		switch ($this->_comodin){	
			case "END_WITH":
				$value = "'%$value'";
			break;
			case "CONTAINS":
				$value = "'%$value%'";
			break;			
			default:
			case "BEGIN_WITH":
				$value = "'$value%'";
		}
		$field = Verifier::verifySelect($this->_field);
		return $field . " LIKE " . $value;
	}
}
