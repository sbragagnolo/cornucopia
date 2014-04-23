<?php
include_once"Verifier.php";
include_once"Criteria.php";
include_once "../session/SessionObject.php";

/**
 * Pasarlo a almenos 2 objetos, uno uqe resuelva operaciones simples y otro el between.
 **/

class Compare implements Criteria {
	protected $_field;
	protected $_value;
	protected $_op;
	
	protected $_valueB;
	
	
	public function toArray(){
		$array = array ();
		$array["Compare_field"]= $this->_field;
		$array["Compare_value"]= $this->_value;
		$array["Compare_valueB"]= $this->_valueB;
		$array["Compare_op"]= $this->_op;
		return $array;
	}
	
	public function loadArray($array) {
		$this->_field = $array["Compare_field"];
		$this->_value = $array["Compare_value"];
		$this->_valueB = $array["Compare_valueB"];
		$this->_op = $array["Compare_op"];
		
		return $this; 
	}
	
	public function __construct($field, $value, $op ="=", $valueB = 'not'){
		$this->_field = $field;
		$this->_value = $value;
		$this->_op = $op;
		$this->_valueB = $valueB;
	}
	
	/**
	 *@return Compare
	 * */
	public static function equals ($field,$value){
		return new Compare($field, $value,"=");
	}
	/**
	 *@return Compare
	 * */ 
	public static function lesser ($field,$value){
		return new Compare($field, $value,"<");
	} 
	/**
	 *@return Compare
	 * */
	public static function greater ($field,$value){
		return new Compare($field, $value,">");
	} 
	/**
	 *@return Compare
	 * */
	public static function lesserEq ($field,$value){
		return new Compare($field, $value,"<=");
	} 
	/**
	 *@return Compare
	 * */
	public static function greaterEq ($field,$value){
		return new Compare($field, $value,">=");
	} 
	/**
	 *@return Compare
	 * */
	public static function in ($field,$value){
		return new Compare($field, $value,"in");
	}
	/**
	 *@return Compare
	 * */
	public static function between ($field,$valueA, $valueB){
		return new Compare($field, $value,"between", $valueB);
	}
	
	
	public function setValue ($value){
		/*
		 * @todo ver que garcha hacer con el between. 
		 * */
		$this->_value = $value;	
	}
	
	
	public function sql () {
		
		$val = $this->value;
		if (empty($val)) return " 1 = 1 ";
		if ($this->_op =="between"){
			return Verifier::verifySelect($this->_field) 
				."BETWEEN"
				. Verifier::acutes(Verifier::verifySelect($this->_value)) 
				."AND"
				. Verifier::acutes(Verifier::verifySelect($this->_valueB)); 	
		}
		return Verifier::verifySelect($this->_field) ." "
			 . Verifier::verifyOperator($this->_op) ." "
			 . Verifier::acutes(Verifier::verifySelect($this->_value));
	}
	
	
	
}