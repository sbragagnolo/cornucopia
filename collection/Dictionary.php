<?php

include_once "Collection.php";

class Dictionary extends Collection {
	
	public function add($key, $value){
		$this->_array[$key] = $value;
	}
	
	public function at ($key){
		return $this->_array[$key];
	}
	public function rewind(){
		$this->_index = 0;
	}
	
	public function current() {
		$k = array_keys($this->_array);
		$var = $this->_array[$k[$this->_index]];

		return $var;
	}

	public function key() {
		$k = array_keys($this->_array);
		$var = $k[$this->_index];
		return $var;
	}

	public function next() {
		$k = array_keys($this->_array);
		
		if (isset($k[++$this->_index])) {
			$var = $this->_array[$k[$this->_index]];
			return $var;
		} else {
			return false;
		}
	}

	public function valid() {
		$k = array_keys($this->_array);
		$var = isset($k[$this->_index]);
		return $var;
	}
	
	public function containsKey($key){
		return array_key_exists($key, $this->_array);
	}
	/**
	 * @param $apply function
	 * apply recibe una function y la aplica a cada componente.
	 **/
	public function apply ( $apply ){
		foreach($this->_array as $key => $value) {
			$apply($key,$value);
		}
	}
	
	/**
	 * @param $selector function
	 * @return Dictionary
	 * select recibe una function booleana, retorna una coleccion de los datos que dan true al selector.
	 **/
	
	public function select ( $selector ) {
		$array = array();
		foreach($this->_array as $key => $value){
			if ($selector($key,$value)){
				$array[$key]=$value;
			}
		}
		return $this->cloneType($array); 
	}
	
	
	/**
	 * @param $collector function
	 * @return Dictionary
	 * collect recibe una function colectora, retorna una nueva coleccion de datos 'procesados' 
	 **/
	
	public function collect ( $collector ) {
		$array = array();
		foreach($this->_array as $key => $value){
			$array[$key]=$collector($key, $value);
		}
		return $this->cloneType($array); 
	}
	
	/*
	 * 
	 * */
	public function inyectInto ($baseValue, $inyector){
		$result = $baseValue;
		foreach($this->_array as $key => $value){
			$inyector($key, $value, $result);
		}
		return $result;
	}
}