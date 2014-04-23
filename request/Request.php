<?php
class Request {
	
	public static function isPost () {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public static function isGet () {
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}
	
	public static function post () {
		return $_POST;
	}
	
	public static function get () {
		return $_GET;
	}
	
	public static function parameters () {
		return $_GET + $_POST;
	}
	
	public static function parameter($name, $default = false){
		$params = Request::parameters();
		if (isset($params[$name])) return $params[$name];
		return $default;
	}
}