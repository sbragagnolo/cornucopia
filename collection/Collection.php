<?php

class Collection implements SeekableIterator{
	protected $_array;
	protected $_index;

	public function __construct(array $array = array()){
		$this->_array = $array;
		$this->_index = 0;
	}

	public function add($value){
		$this->_array[] = $value;
	}

	public function seek ($position) {
		$this->_index = $position;
	}

	public function rewind() {
		$this->_index = 0;
		return $this;
	}
	public function current() {
		return $this->_array[$this->_index];
	}

	public function key() {
		return $this->_index;
	}

	public function next() {
		if ($this->valid() === false) {
			return null;
		}
		if (isset($this->_array[++$this->_index])) {
			return $this->_array[$this->_index];
		} else {
			return false;
		}
	}

	public function valid() {
		return isset($this->_array[$this->_index]);
	}


	public function cloneType ($array = array()){
		$class = get_class($this);
		return new $class($array);
	}

	/**
	 * @param $apply function
	 * apply recibe una function y la aplica a cada componente.
	 **/
	public function apply ( $apply ){
		foreach($this->_array as $value) {
			$apply($value);
		}
	}
	/**
	 * @param $selector function
	 * @return Collection
	 * select recibe una function booleana, retorna una coleccion de los datos que dan true al selector.
	 **/

	public function select ( $selector ) {
		$array = array();
		foreach($this->_array as $value){
			if ($selector($value)){
				$array[]=$value;
			}
		}
		return $this->cloneType($array);
	}


	/**
	 * @param $collector function
	 * @return Collection
	 * collect recibe una function colectora, retorna una nueva coleccion de datos 'procesados'
	 **/

	public function collect ( $collector ) {
		$array = array();
		foreach($this->_array as $value){
			$array[]=$collector($value);
		}
		return $this->cloneType($array);
	}

	/*
	 *
	 * */
	public function inyectInto ($baseValue, $inyector){
		$result = $baseValue;
		foreach($this->_array as $value){
			$inyector($value, &$result);
		}
		return $result;
	}
}