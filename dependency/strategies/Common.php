<?php
include_once "InstanceStrategy.php";

class Common implements InstanceStrategy {
	protected static $_instance = array();
	
	protected $_reflectMap = array();
	
	public static function StrategyInstance () {
		if (!self::$_instance){
			self::$_instance = new self(); 
		}
		return self::$_instance;
	}
	
	public function getReflectedClass($class){
		if (!array_key_exists($class,$this->_reflectMap)){
			$this->_reflectMap [$class]= new ReflectionClass($class); 
		}
		return $this->_reflectMap [$class];
	}
	public function instance($class, $args){
		if (count($args)>0){
			return $this->getReflectedClass($class)->newInstanceArgs($args);
		}
		return new $class();
	}
} 
