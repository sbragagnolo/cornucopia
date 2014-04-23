<?php
include_once "InstanceStrategy.php";

class Deny implements InstanceStrategy {
	protected static $_instance = false;
	
	protected function __construct () {
		$this->_instances = array();
	}
	
	public static function StrategyInstance () {
		if (!self::$_instance){
			self::$_instance = new self(); 
		}
		return self::$_instance;
	}
	
	public function instance($class, $args){
		throw new exceptions\Program("La instanciacion de $class esta denegada");
	}
} 