<?php
include_once "Validator.php";

class Len implements Validator {
	protected $_len;
	protected $_jscript;
	public function __construct($len){
		$this->_len = $len;
		$this->_jscript = Container::instance("LenJSValidator", $len);
	}
	
	public function validate(Component $component){
		return strlen($component->value) <= $this->_len;
	}
	
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);	
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Se esperaban como mucho $this->_len caracteres";
	}
}