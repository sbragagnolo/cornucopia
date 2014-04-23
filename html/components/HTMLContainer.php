<?php

include_once "Component.php";


abstract class HTMLContainer extends Component{

	protected $_contain;
	
	public function __construct($contains = false, $config = array()){
		parent::__construct();
		$this->_data = $config;
		if(!$contains){
			$contains = new BasicObject();
		}
		$this->_contain = $contains;
	}
	
	public function useContain (BasicObject $contains){
		$this->_contain = $contains;
		return $this;
	}
	
	public function add ($component) {
		$at = $component->name;
		$this->_contain->$at = $component;
		return $this;
	}
	
	public function elements() {
		return $this->_contain;
	}
	
	public function isValid () {
		$contain = $this->_contain->toArray();
		foreach ($contain as $component){
			if (!$component->isValid()){
				return false;
			}
		}
		return true;
	}
	public function loadErrors(){
		$contain = $this->_contain->toArray();
		
		foreach ($contain as $component){
			
			$component->loadErrors();
		
		}
	}
	
	
	public function loadValueFromArray($array){
		$contain = $this->_contain->toArray();
		foreach ($contain as $component){
			$component->loadValueFromArray($array);
		}
	}
	
	public function inyectValueInto ($object){
		$contain = $this->_contain->toArray();
		foreach ($contain as $component){
			$component->inyectValueInto($object);
		}
		return $object;
	}
}
