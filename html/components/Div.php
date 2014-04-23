<?php

include_once ("HTMLContainer.php");


class Div extends HTMLContainer {

	public function render() {
		
		$div = "<div";
		foreach ($this->_data as $k => $v){
			$div .= ' '. $k . '="' . $v. '"';
		}
		foreach ($this->untaged() as $value){
			$div .= " $value "; 
		}
		$div .= " >";
		
		foreach($this->_contain as $component) {
			$div .= $component->__toString(); 
		}
		
		$div .= " 
		</div>";
		return $div;
	}
}
