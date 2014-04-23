<?php
include_once "BasicJQueryValidator.php";


class JQNumeric extends  BasicJQueryValidator {
	
	
	public function __construct(){
		parent::__construct("numeric");
	}
	
	public function getErrorMsg(Component $component){
		return " Se esperaba valor numerico ";
	}
}


class JQAlphabethic extends BasicJQueryValidator {

	public function __construct(){
		parent::__construct("alphabethic");
	}
	
	public function getErrorMsg(Component $component){
		return " Se esperaban solo letras ";
	}
}


class JQAlphanumeric extends BasicJQueryValidator {
	public function __construct(){
		parent::__construct("alphanumeric");
	}
	
	public function getErrorMsg(Component $component){
		return " Se esperaban letras y/o numeros ";
	}
}


class JQEmail extends BasicJQueryValidator {
	public function __construct(){
		parent::__construct("email");
	}
	
	public function getErrorMsg(Component $component){
		return " No es un mail valido ";
	}
}


class JQPassword extends BasicJQueryValidator {
	public function __construct(){
		parent::__construct("password");
	}
	
	public function getErrorMsg(Component $component){
		return " El password no es seguro ";
	}
}


class JQUrl extends BasicJQueryValidator {
	public function __construct(){
		parent::__construct("url");
	}
	
	public function getErrorMsg(Component $component){	
		return " No es una url valida";
	}
}


class JQDate extends BasicJQueryValidator {
	public function __construct(){
		parent::__construct("date");
	}
	
	public function getErrorMsg(Component $component){
		return " No es una fecha valida ";
	}
}

