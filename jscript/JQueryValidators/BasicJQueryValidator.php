<?php
include_once "../jscript/JSValidator.php";

define("EMPTY_JS_CALL", "");

class BasicJQueryValidator extends BasicObject implements JSValidator {	
	
	public function __construct($class){
		$this->class = $class;
	}
	
	public function jsValidate($component){
		foreach($this->_data as $k => $v) {
			$component->$k = $v;
		}
		return EMPTY_JS_CALL;
	}
	
}



