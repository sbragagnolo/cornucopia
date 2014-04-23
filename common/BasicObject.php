<?php
include_once "../session/SessionObject.php";

class BasicObject implements SessionObject{
	protected $_data = array();
	
	
	public function __isset($k){
		return array_key_exists($k, $this->_data);
	}
	public function __set($k,$v){
		$k = strtolower($k);
		$this->_data[$k] = $v;
	}
	public function __get($k){
		$k = strtolower($k);
		return $this->_data[$k];
	}
	
	public function toArray() {
		return $this->_data;
	}
	
	public function loadArray($array) {
		$this->_data = $array;
	}
}