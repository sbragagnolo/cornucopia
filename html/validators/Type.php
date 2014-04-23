<?php
include_once "Validator.php";


class Arrays implements Validator {
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("ArrayJSValidator");
	}
	public function validate(Component $component){
		
		return is_array($componet->value);
	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Se esperaba array ";
	}
}


class Numeric implements Validator {
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("NumericJSValidator");
	}
	
	public function validate(Component $component){
		return is_numeric($component->value);
	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Se esperaba valor numerico ";
	}
}


class Alphabethic implements Validator {
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("AlphabeticJSValidator");
	}
	public function validate(Component $component){

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Se esperaban solo letras ";
	}
}


class Alphanumeric implements Validator {
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("AlphanumericJSValidator");
	}
	public function validate(Component $component){

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " Se esperaban letras y/o numeros ";
	}
}


class Email implements Validator {
	
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("EmailJSValidator");
	}
	
	public function validate(Component $component){

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " No es un mail valido ";
	}
}


class Password implements Validator {
	
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("PasswordJSValidator");
	}
	public function validate(Component $component){

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}

	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " El password no es seguro ";
	}
}


class Url implements Validator {
	
	protected $_jscript;
	
	public function __construct () {
		$this->_jscript = Container::instance("URLJSValidator");
	}
	public function validate(Component $component){

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}
	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " No es una url valida";
	}
}


class Date implements Validator {
	protected $_format;
	protected $_delimiter;
	protected $_jscript;
	
	
	public function __construct($delimiter="-",$format ="d-m-Y"){
		$this->_format = $format;
		$this->_delimiter = $delimiter;
		$this->_jscript = Container::instance("DateJSValidator");
	}

	public function validate(Component $component){
		$fmtPieces = explode($this->_delimiter, $this->_format);

		$map = array();
		$i = 0;
		foreach($fmtPieces as $piece){
			$map[$piece] = $i;
			$i++;
		}

		if (!(	array_key_exists('m',$map)
		&&  array_key_exists('d',$map)
		&&  array_key_exists('Y',$map))){
			return false;
		}

		$datePieces = explode($this->_delimiter, $component->value);

		$mes = $datePieces[$map['m']];
		$dia = $datePieces[$map['d']];
		$anio = $datePieces[$map['Y']];

		return mktime(0,0,0,$mes,intval($dia),$anio);

	}
	public function jsValidate(Component $component){
		return $this->_jscript->jsValidate($component);
	}

	public function getErrorMsg(Component $component){
		if ($this->validate($component)) return "";
		return " No es una fecha valida ";
	}
}

