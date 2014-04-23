<?php

class Page extends HTMLContainer {
	protected $_footer;
	protected $_header;
	
	
	public function __construct($title = "Title", $template = false){
		parent::__construct();
		$this->_template = $template;
		$this->title = $title;
		$this->_footer = array();
		$this->_header = array(); 
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle () {
		return $this->title;
	}
	
	public function addToHeader($component){
		$this->_header[] = $component;
	}
	public function addToFooter($component){
		$this->_footer[] = $component;
	}
	
	public function getFooterContainer() {
		return $this->_footer;
	}
	
	public function getHeaderContainer() {
		return $this->_header;
	} 
	
	public function render(){
		
		$componentes = "";
		
		$contain = $this->_contain->toArray();
		foreach ($contain as $component){
			$componentes .= " ".$component;
		}

		return $componentes;
	}
	
	
}