<?php

include_once "Form.php";
include_once "../db/repository/criteria/From.php";
include_once "../db/repository/criteria/CriteriaAND.php";

class CriteriaForm extends Form {
	protected $_criterias = array();
	
	public static function Form ($method, $class ='', $name ="form"){
		return new CriteriaForm($method, $class, $name);
	}
	
	public function add ($component, $criteria = false) {
		parent::add($component);
		if (!$criteria) return $this;

		$this->_criterias[$component->name] = $criteria;
		return $this;
	}

	
	public function getCriteria () {
		$criterias = array();
		foreach ($this->_criterias as $name => $criteria){
			$component = $this->_contain->$name; 
				if($component->isSetted()){
					$criteria->setValue($component->value);
					$criterias[] = $criteria;
				}
		}
		
		return From::From($this->_class)->add(new CriteriaAND ($criterias));
	}
	
}