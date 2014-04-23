<?php
include_once "Proxy.php";

/*
 * Los siguientes includes son para no tener que hacerlos en el codigo de negocio, 
 * y no tener que usar el container para esto.
 * */
include_once "criteria/Access.php";
include_once "criteria/Compare.php";
include_once "criteria/Criteria.php";
include_once "criteria/CriteriaAND.php";
include_once "criteria/CriteriaOR.php";
include_once "criteria/Expression.php";
include_once "criteria/From.php";
include_once "criteria/Like.php";
include_once "criteria/Verifier.php";

class Repository {
	
	protected $_connection;
	protected $_proxiesMap; 
	
	public function __construct() {
		$this->_connection = Container::instance("Connection");
		$this->_proxiesMap = new Dictionary();
		
	}

	/**
	 * @return Collection
	 * */
	public function retrieve (Access $access) {
		$retrieves = $this->_connection->query ($access->sql());
		
		$class = $access->_accessClass();
		$nestedProxy = $this->getProxyPrototype($class);

		$return = new Collection();

		$i = 0;
		foreach ($retrieves as $k => $array) {
			$i++;
			$proxy = $nestedProxy->prototypeFor($array);
			$return->add($proxy);
		}
		
		return $return;
	}
	
	
	/**
	 * @return Collection
	 * */
	public function retrieveBasicObjects ($query) {
		include_once "../common/BasicObject.php";
		$retrieves = $this->_connection->query ($query);

		$return = new Collection();
		
		$i = 0;
		foreach ($retrieves as $k => $array) {	
			$i++;
			$object = new BasicObject();
			$object->loadArray($array);	
			$return->add($object);
		}
		if ($i == 1) return $object; 
		return $return;
	}
	
	/**
	 * @return Proxy
	 * */
	public function getProxyPrototype($class){
		$proxy = $this->_proxiesMap->at($class);
		if (!$proxy) {
			$proxy = new Proxy($class);
			$this->_proxiesMap->add($class, $proxy);
		}
		return $proxy;
	}
	
	/*
	 * 
	 * */
	public function saveArray($table, $array, $pkGetter){
		$pk = $pkGetter($array);
		
		if ($pk) {
			$this->_update($table, $array);
			return $pk;
		}
		return $this->_insert($table, $array);

	}
	
	protected function _update ($table, $array){
		
		$where = array();
		foreach ($array as $k => $v){
			$where []= Verifier::verifySelect($k) . " = " . Verifier::acutes(Verifier::verifySelect($v)); 
		}
		$table = Verifier::verifySelect($table);
		$where = implode(" AND ", $where);
		if (count($where) > 0) {
			$where = " WHERE " . $where;
		}  
		
		$this->_connection->execute(
		" UPDATE 
		  FROM $table 
		  $where 
		  LIMIT 1"
		  );
	}
	protected function _insert($table, $array){
		
		$keys =array();
		$vals =array();
		 
		foreach($array as $k => $v) {
			$keys[]=$k;
			$vals[]=Verifier::acutes(Verifier::values($v));
		}
		$table = Verifier::verifySelect($table);
		
		$keys = implode(",", $keys);
		$vals = implode(",", $vals);

		$this->_connection->execute ("INSERT INTO $table ($keys) VALUES ($vals)");
		
		return $this->_connection->getLastId();
	}
	
	public function save ($object){
		if ($object instanceof Proxy){
			$proxy = $object;
		}else {
			$class = get_class($object);
			$proxy = $this->getProxyPrototype($class)->prototypeForObject($object);			
		}
		
		return $proxy->save($this);
	}
}
