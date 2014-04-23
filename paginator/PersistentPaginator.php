<?php

include_once "Paginator.php";

class PersistentPaginator extends Paginator{
	
	
	public function toArray() {
		$array = parent::toArray();
		$array['Paginator_access'] = $this->_access->toArray();
		return $array;
		
	}
	public function loadArray($array) {
		parent::loadArray($array);
		$this->_access = From::From("");
		$this->_access->loadArray($array);
		return $this;
	}
	
}

