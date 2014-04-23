<?php

include_once "../common/BasicObject.php";



abstract class Component extends BasicObject {
	protected $_validators = array();
	protected $_untaged = array();
	protected $_template = false;
	protected $_errors = "";


	public function __construct() {
	}

	public function untaged() {
		return $this->_untaged;
	}
	public function addUntaged ($value){
		$this->_untaged[] = $value;
	}

	public function removeUnTaged($value){
		$untaggs = array();
		foreach($this->untaged() as $untaged) {
			if ($untaged != $value){
				$untaggs[] = $untaged;
			}
		}
		$this->_untaged = $untaggs;
	}
	public function addValidator($validator) {
		$this->_validators [] = $validator;
		return $this;
	}
	
	public function isSetted(){
		return isset($this->value);
	}

	public function __get($k){
		return $this->_data[strtolower($k)];
	}

	public function __set($k,$v){
		$k = strtolower($k);
		if ( $k == "class" && array_key_exists("class",$this->_data)){
			$this->_data[$k] = $this->_data[$k]. ' ' . $v;
		}else {
			$this->_data[$k] = $v;
		}
	}

	public function isValid () {
		foreach($this->_validators as $validator){
			if (!$validator->validate($this)) {
				return false;
			}
		}
		return true;
	}
	
	public function jsValidatorCalls(){
		$calls = "";
		foreach($this->_validators as $validator){
			$calls .= $validator->jsValidate($this);
		}
		return $calls;
	}

	public function loadErrors(){
		foreach($this->_validators as $validator){
			$this->_errors .= $validator->getErrorMsg($this) . "<br/>";
		}
	}

	public function loadValueFromArray($array){
		$this->value = $array[$this->name];
	}
	public function inyectValueInto ($object){
		$name = $this->name;
		$object->$name = $this->value;
		return $object;
	}

	public function useTemplate ($template){
		$this->_template = $template;
		return  $this;
	}
	public function __toString(){
		$calls = $this->jsValidatorCalls();
		
		if($this->_template) {
			return $this->_applyTemplate();
		}
		
		return $this->render();
	}

	abstract public function render();

	public function _applyTemplate (){
		if (file_exists($this->_template)) {
			$template = file_get_contents($this->_template);
			ob_start();
			eval("?> " . $template . " <?php");
			return ob_get_clean();

		}else {
			echo "El template $this->_template no existe";
			return false;
		}
	}

	public function getErrors () {
		return $this->_errors;
	}


}






