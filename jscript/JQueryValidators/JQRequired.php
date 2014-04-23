<?php
include_once "BasicJQueryValidator.php";

class JQRequired extends BasicJQueryValidator {
	
	public function __construct(){
		parent::__construct("required");
	}
	
	public function getErrorMsg(Component $component){
		return " Campo obligatorio ";
	}
}