<?php

include_once "strategies/Common.php";
include_once "strategies/Deny.php";
include_once "strategies/Singleton.php";
include_once "strategies/InstanceStrategy.php";


/*
 * Al final del archivo hay un include al ../config/base.config.php archivo que hace 
 * algunas configuraciones iniciales para el container.  
 * 
 * */




class Container {
	protected static $_instance;
	protected static $_Common;
	protected static $_Deny;
	protected static $_Singleton;

	protected $_configuration;


	public static function Common (){
		if( ! Container::$_Common ) {
			Container::$_Common = Common::StrategyInstance();
		}
		return Container::$_Common;
	}

	public static function Deny (){
		if( ! Container::$_Deny ) {
			Container::$_Deny = Deny::StrategyInstance();
		}
		return Container::$_Deny;
	}
	public static function Singleton (){
		if( ! Container::$_Singleton ) {
			Container::$_Singleton = Singleton::StrategyInstance();
		}
		return Container::$_Singleton;
	}

	protected function __construct() {
		include_once "../collection/Dictionary.php";
		$this->_configuration = new Dictionary();
	}

	public static function instance(){

		$args = func_get_args();
		if (count($args) == 0) {
			throw new Program ("Container::instance recibe al menos un parametro");
		}
		$name = $args[0];
		array_shift($args);

		$container = Container::containerInstance();
		return $container->_instance($name, $args);
	}

	public static function containerInstance () {
		if (!Container::$_instance) {
			Container::$_instance = new Container();
		}
		return Container::$_instance;
	}

	protected function _getConfiguration ($class) {
		if (! $this->_configuration->containsKey($class) ){
			include_once "../exceptions/Business.php";
			throw new Business(" no existe la configuracion para $class en el container ");
		}
		return $this->_configuration->at($class);
	}

	public function _instance ($name, $args){
		return $this->_getConfiguration($name)->instance($args);
	}

	public function registerProcesor($class, $procesor){
		$this->_getConfiguration($name)->registerProcesor($procesor);
	}


	public static function inferredPathRegister ($name, $class, $strategy, $processors = array()){
		return Container::register($name, $class,null, $strategy, $processors);
	}

	public static function register ($name, $class, $includePath, $strategy, $processors = array()){
		$container = Container::containerInstance();
		$container->_configuration->add($name, new Configuration ($class,$includePath, $strategy, $processors));
		return $container;
	}

}

class Configuration {
	protected $_class;
	protected $_strategy;
	protected $_includePath;
	protected $_included;
	protected $_processors;

	/*
	 * Procesor: Function que recibe un objeto de la clase
	 * */


	public function __construct($class,$includePath, $strategy, $processors){
		$this->_class = $class;
		$this->_strategy = $strategy;
		$this->_includePath = $includePath;
		$this->_processors = $processors;
	}


	public function _filePatterns () {
		$pointedClass = implode('/', explode('.', $this->_class));

		return array ($this->_class, $pointedClass);
	}

	public function includeClass () {
		if ($this->_included) return;
		if ($this->_includePath != null) {
			include_once $this->_includePath;
			$this->_included = true;
		}
		else {
			/*
			 * Por cada posible 'filePattern' se busca en cada path a ver si existe un .php o un .class.php
			 * */
			foreach($this->_filePatterns($this->_class) as $name){
				foreach(explode(PATH_SEPARATOR,get_include_path()) as $paths){
					if (file_exists($paths.DIRECTORY_SEPARATOR.$name.'.php')){
						include_once ($paths.DIRECTORY_SEPARATOR.$name .'.php');
						$this->_included = true;
						return;
					}
					if (file_exists($paths.DIRECTORY_SEPARATOR.$name.'.class.php')){
						include_once ($paths.DIRECTORY_SEPARATOR.$name .'.class.php');
						$this->_included = true;
						return;
					}
				}
			}

			include_once "../exceptions/Business.php";
			throw new Business(" no se encontro el archivo de inclusion para la clase ". $this->_class);
		}
	}


	public function registerProcesor($processors){
		$this->_processors[]=$processors;
	}

	public function instance ($args) {
		$this->includeClass();
		$instance = $this->_strategy->instance($this->_class, $args);
		foreach($this->_processors as $processor){
			$processor($instance);
		}
		return $instance;
	}
}



include_once "../config/base.config.php";








