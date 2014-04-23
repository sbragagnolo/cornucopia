<?php
include_once "Criteria.php";
include_once "../session/SessionObject.php";

class CriteriaOR implements Criteria, SessionObject {
protected $_criterias;

	public function __construct(Array $criterias) {
		$this->_criterias = $criterias;
	}

	public function sql () {
		
		$sqls = array();
		foreach ($this->_criterias as $criteria){
			$sqls []= $criteria->sql();
		}
		
		return "(" . implode(" OR ", $sqls) . ")";
	}
	public function setValue ($value){
		throw new Program(" set value no aplica a Criteria AND");
	}
	
	public function toArray () {
		$array = array();
		
		$array["AND_criterias"] = array();
		foreach ($this->_criterias as $criterias){
			if (!isset($array["AND_criterias"][get_class($criterias)])){
				$array["AND_criterias"][get_class($criterias)] = array();
			}
			$array["AND_criterias"][get_class($criterias)][] = $criterias->toArray(); 
		}
		return $array;
	}
	
	public function loadArray($array) {
		foreach ($array["AND_criterias"] as $class => $objects){
			foreach ($objects as $subarray ){
				$restriction = new $class ();
				$restriction->loadArray($subarray);
				$this->_criterias [] = $restriction;
			} 
		}
		return $this;
	}
}