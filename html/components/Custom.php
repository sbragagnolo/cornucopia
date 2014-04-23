<?php

include_once ("Component.php");


class Custom extends Component {
	protected $_str;
	protected static $instances = 0; 
	public static function Custom($string){
		return new Custom($string);
	}
	public function __construct($str){
		parent::__construct();
		$this->_str = $str;
		$this->name = "custom_" . self::$instances;
		self::$instances++;
	}

	public function render(){
		return $this->_str . $this->getErrors();
	}
	public function loadValueFromArray($array){
		return;
	}
} 