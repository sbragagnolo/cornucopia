<?php
include_once "Validator.php";

class MultipleValidator implements Validator {
	protected $_verifiers = array();
	
	public function add($verifier){
		$this->_verifiers[] = $verifier;
		return $this;
	}
	public function validate(Component $component){
		foreach($this->_verifiers as $verifier){
			if (!$verifier->validate($component)){
				return false;
			}
		}
		return true;
	}
	
	public function jsValidate(Component $component){
		
		$calls = " ";
		foreach($this->_verifiers as $verifier){
			$calls .= $verifier->jsValidate($component);	
		}
		return $calls;
	}

}