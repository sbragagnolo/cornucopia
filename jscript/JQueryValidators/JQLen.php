<?php
include_once "BasicJQueryValidator.php";

class JQLen extends BasicJQueryValidator {
	protected $_len;
	
	public function __construct($len){
		$this->_len = $len;
		parent::__construct("");
		$this->minlength="2";
	}
	
	public function getErrorMsg(Component $component){
		return " Se esperaban como mucho $this->_len caracteres";
	}
}