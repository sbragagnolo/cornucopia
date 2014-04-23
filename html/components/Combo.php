<?php

include_once ("Component.php");

class Combo extends Component{

	protected $_options;
	protected $_selected;
	
	public static function Combo ($name, $options = array(), $selected = ""){
		return new Combo($name, $options, $selected);
	}
	public function __construct($name, $options = array(), $selected = ""){
		parent::__construct();
		$this->name = $name;
		$this->useOptions($options, $selected);
	}
	
	public function useOptions ($options, $selected=""){
		$this->_options = $options;
		$this->_selected = "";
	}
	
	public function addOption($option) {
		$this->_options [] = $option;
		return $this;
	}
	
	public function render() {
		$select = "<select";
		foreach ($this->_data as $k => $v){
			$select .= ' '. $k . '="' . $v. '"';
		}
		foreach ($this->untaged() as $value){
			$select .= " $value "; 
		}
		$select .= " >";
		
		foreach($this->_options as $k=>$v) {
			$select .= ' <option value="' . $k . '" ' . ($k == $this->_selected? "selected":"")."> $v </option> 
			";
		}
		$select .= " 
		</select>" . $this->getErrors();
		return $select;
	}
	
}
