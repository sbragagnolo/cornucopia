<?php
include_once "Validator.php";

class Required implements Validator {
	protected $_jscript;
	
	public function __construct(){
		$this->_jscript = Container::instance("RequiredJSValidator");
	}
	
	public function validate(Component $component){
		$value = $component->value;
		return !empty($value);
	}
	
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);	
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Campo obligatorio ";
		
	}
}