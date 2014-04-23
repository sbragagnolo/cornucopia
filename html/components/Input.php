<?php

include_once ("Component.php");

class Submit extends Input {
	public function loadValueFromArray($array){}
}
class Button extends Input {
	public function loadValueFromArray($array){}
}

class Input extends Component {
	
	public function __construct($type, $name, $value) {
		parent::__construct();
		$this->_data['type'] = $type;
		$this->_data['name'] = $name;
		$this->_data['value'] = $value;
	}
	public function loadValueFromArray($array){
		if ($this->type == "checkbox" ||$this->type == "radio" ){
			if (empty($array[$this->name])){
				$this->removeUnTaged("checked");
			}
		}
		else {
			parent::loadValueFromArray($array);
		}
	}
	public static function Button ($name,$value){
		return new Input ("button", $name, $value);
	}
	public static function Checkbox ($name,$value, $checked = false ){
		$input = new Input ("checkbox", $name, $value);
		if ($checked) $input->addUntaged("checked"); 
		return $input;
	}  
	public static function File ($name,$value){
		return new Input ("file", $name, $value);
	}  
	public static function Hidden ($name,$value){
		return new Input ("hidden", $name, $value);
	}  
	public static function Image ($name,$value){
		return new Input ("image", $name, $value);
	}  
	public static function Password ($name,$value){
		return new Input ("password", $name, $value);
	}  
	public static function Radio ($name,$value, $checked = false ){
		$input = new Input ("radio", $name, $value);
		if ($checked) $input->addUntaged("checked"); 
		return $input;
	}  
	public static function Reset ($name,$value){
		return new Input ("reset", $name, $value);
	}    
	public static function Submit ($name,$value){
		return new Submit ("submit", $name, $value);
	}    

	public static function Text ($name,$value){
		return new Input ("text", $name, $value);
	}    
	
	public function render() {

		$input = "<input ";
		foreach ($this->_data as $k=>$v){
			$input .= $k .'="' .$v.'" ';
		}
		foreach ($this->untaged() as $value){
			$input .= " $value "; 
		}
		return $input . " />" . $this->getErrors();
	}
}