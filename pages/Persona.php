<?php

class Persona //extends BasicObject 
{
	public $_hasOne = array(array("Cuenta", "getCuenta"));
	protected $id;
	protected $nombre;	
	protected $apellido;
	protected $dni;

	public function __set($k, $v){
		$this->$k = $v;
	}
	public function __get($k){
		return $this->$k;
	}


	
	
	
}

