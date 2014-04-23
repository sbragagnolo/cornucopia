<?php



class Session {
	
	static protected $_strict = true;
	
	 public static function setStrictTypes ($set){
		self::$_strict = $set;
	}	
	public static function set($name, $value){
		if (is_object($value)){
			$type = "native";
		}
		else {
			$type = get_class($value);
			print_r (serialize($value));
			exit;
		}
		$_SESSION[$name] = array ("value" => $value, "type" => $type); 
		
		
	}
	
	public static function erase($name){
		unset($_SESSION[$name]);
	}
	
	public static function loadIn($name, $object){
		
		$data = $_SESSION[$name];
		print_r($data);exit;
		if (empty($data)) return false;
		$object->loadArray($data["value"]);
	}
	
	public static function get($name){
		$data = $_SESSION[$name];
		if (empty($data)) return false;
		if($data["type"] == "native") return $data["value"];
		try {
			$object = Container::instance($data["type"]);
		}
		catch (Business $e){
			if (self::$_strict) {
				throw new Business(" La clase del objeto $name debe estar registrada en el container");
			}
			include_once "../common/BasicObject.php";
			$object = new BasicObject();
		}
		$object->loadArray($data["value"]);
		return $object;
	}
}