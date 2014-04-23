<?php
include_once "HTMLContainer.php";


class Form extends HTMLContainer {
	protected $_class;
	protected $_perform;
	
	
	public static function Form ($method, $class ='', $name ="form"){
		return new Form($method, $class, $name);
	}
	 
	public static function PerformNothing(){
		return function ($form){};
	} 
	
	
	public function __construct($method, $class ='', $name ="form"){
		parent::__construct($contains, array("method" => $method, "name" => $name));
		$this->_class = $class;
		$this->action = "#";
		$this->_perform = Form::PerformNothing();
	}
	
	public function perform() {
		return $this->_perform($form);
	}
	public function loadPost() {
		$this->loadValueFromArray($_POST);
	}
	
	public function loadGet() {
		$this->loadValueFromArray($_GET);
	}
	
	public function loadObject() {
		include_once "../dependency/Container.php";
		$object = Container::instance($this->_class);
		$this->inyectValueInto($object);
		return $object;
	}
	
	public function isValid(){
		if (parent::isValid()) return true;
		$this->loadErrors();
		return false;
	}
	
	public function render() {
		
		$form = "<form";
		foreach ($this->_data as $k => $v){
			$form .= ' '. $k . '="' . $v. '"';
		}
		foreach ($this->untaged() as $value){
			$form .= " $value "; 
		}
		$form .= " >";
		$contain = $this->_contain->toArray();
		
		foreach($contain as $component) {
			
			$form .= " ".$component; 
		}
		
		$form .= " 
		</form>";
		return $form;
	}
}
