<?php
include_once "InstanceStrategy.php";

class Singleton implements InstanceStrategy {
	protected $_instances;
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
		if(! array_key_exists($class,$this->_instances) ) {
			if (count($args)>0){
				$reflect = new ReflectionClass($class);
				$this->_instances[$class] = $reflect->newInstance($args);
			}
			$this->_instances[$class] = new $class();
		}
		return $this->_instances[$class];
	}
	
} 