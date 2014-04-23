<?php



class Proxy {
	
	/* informacion de mantenimiento */
	
	protected $_isTransient;
	
	/* datos relacionados */
	protected $_data;

	/* objeto propiamente dicho */
	protected $_object;

	
	/* mensajes que entiende el objeto */
	protected $_methods;
	protected $_strictMethods; 
	protected $_attributes;
	
	
	protected $_class;
	protected $_table;

	
	public function __construct ($class, $strict = true) {

		$this->_isTransient = true;
		$this->_data = array();

		
		$this->_strictMethods = $strict;
		$reflect = new ReflectionClass($class);
		$this->_methods = array();
		$this->_attributes = array();
		
		$this->_class = $class;
		$this->_table = $reflect->getName();
		
		
		foreach ($reflect->getMethods() as $method) {
			$this->_methods[$method->name] = $method;

			if ($method->isStatic() && $method->name == "__COLUMN_CONFIGURATION"){
				$this->_attributes = $class::__COLUMN_CONFIGURATION();
			}
			if ($method->isStatic() && $method->name == "__TABLE_CONFIGURATION"){
				$this->_table = $class::__TABLE_CONFIGURATION();
			}
		}
		
		$connection = Container::instance("Connection");
		$tableAttributes = $connection->describe ($this->_table);

		foreach($tableAttributes as $attribute){
			$config = true;
			foreach ($this->_attributes as $att => $arr) {
				if ($arr['name'] == $attribute['Field']) {
					$config = false;
				}
			}
			if ($config){
				$this->_attributes[$attribute['Field']] = array ("Name" => $attribute['Field'], "require" => $attribute['Null'] != "NO", "Type" => $attribute['Type']);
			}
		}
	}
	
	public function getTable () {
		return $this->_table;
	}
	public function toArray () {
		$array = array();
		
		foreach ($this->_attributes as $attribute){
			$name = $attribute['Name'];
			$array[$name] = $this->_object->$name;
		}
		return $array;
	}
	
	/**
	 * @return Proxy
	 * */
	
	public function _getPkGetter() {
		return function ($array)  {
			return $array['id'];
		};
	}
	public function _getPkSetter() {
		return function ($object, $val)  {
			return $object->id = $value;
		};
	}
	
	public function getForeigns(){
		return array();
	}
	
	public function save ($repository) {
		$data = $this->toArray();
		$setter = $this->_getPkSetter();
		$setter($this->_object, $repository->saveArray($this->_table, $data, $this->_getPkGetter()));
		foreach($this->getForeigns() as $foreign){
			$repository->save($foreign);
		}
		return $this;
	}
	
	public function prototypeForObject ($object){
		$newProxy = clone $this;
		$newProxy->_object = $object;
		return $newProxy;
	}
	
	public function prototypeFor ($array){
		$object = Container::instance($this->_class);
		$newProxy = clone $this;
		$newProxy->_object = $object;
		$newProxy->_loadFromAssociativeArray($array);
		return $newProxy;
	}
	
	public function _getAttributeName($k){
		if (!array_key_exists($k,$this->_attributes)) return $k;

		$name = $this->_attributes[$k]["name"];
		if (empty($name)){
			return $k;
		}
		return $name;
	}
	public function _getAttributeValue($attribute, $v){
		return $v;
	}
	
	protected function _loadFromAssociativeArray ($array) {
		foreach($array as $k => $v) {
			$attribute = $this->_getAttributeName($k);
			$value = $this->_getAttributeValue($attribute, $v);
			$this->__set ($attribute, $value);
		}
		$this->_isTransient = false;
		return $this;
	}
	
	public function _isFromOtherTable ($name){
		if (!array_key_exists($k,$this->_attributes)) return $false;
		if(array_key_exists($this->_attributes[$name],"hasMany")) return true;
		if(array_key_exists($this->_attributes[$name],"isOwnedBy")) return true;
		return false;
	}
	public function _resolvValue ($name){
		throw new Program ("<br/> Hay que implementar Proxy::_resolvValue <br/>");
	}
	public function __get ($name){
		if ($this->_isFromOtherTable($name)){
			return $this->_resolvValue($name);
		}
		return $this->_object->$name;
	}
	
	public function __set ($name, $value){
		$this->_object->$name = $value;
	}
	
	public function getClass(){
		return $this->_class;	
	}
	public function getAttributes(){
		return $this->_attributes;
	
	}
	public function __call($name, $args){

		if ($this->_strictMethods){
			if (array_key_exists($name, $this->_methods)){
				return $this->_methods[$name]->invokeArgs ($this->_object, $args);
			}
		}
		$class = $this->getClass();
		throw new Program("Se intento mandar el mensaje $name a un objeto de la clase $class ");

	}
}


