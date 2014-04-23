<?php

include_once "../html/components/Link.php";
include_once "../request/Request.php";
include_once "../session/Session.php";
include_once "../session/SessionObject.php";

class Paginator extends Component implements SessionObject{
	protected $_batchSize;
	protected $_urlBase;
	protected $_pages;
	protected $_access;
	protected $_sessionName;
	
	
	public function __construct (Access $access, $url= "", $sessionName = "paginator"){
		$this->_access =$access;
		$this->_sessionName = $sessionName;
		$this->_urlBase = $url;
	}
	
	
	public function tryToLoadSession (Access $access = null, $sessionName= "paginator") {
		//Session::loadIn($sessionName, $this);
	} 
	
	public function setBatchSize($size){
		$this->_batchSize = $size;
	}
	public function getBatchSize() {
		if (!$this->_batchSize) {
			throw new Program (" No esta seteado Batchsize del paginator " . $this->_sessionName);
		}
		return $this->_batchSize;
	}
	public function setUrlBase($url){
		$this->_urlBase = $url;
	}

	
	public function getSelectedPage() {
		return Request::parameter("page_id", 0);
	}

	public function retrieve () {
		
		$sel = $this->getSelectedPage();
		$this->_access->limit($this->getSelectedPage() * $this->getBatchSize(), $this->getBatchSize());
		$repo = Container::instance("Repository");
		return $repo->retrieve($this->_access);
	}
	
	public function toArray() {
		$array = array();
		$array['Paginator_batchSize'] = $this->_batchSize;
		$array['Paginator_pages'] = $this->_pages;
		return $array;
		
	}
	public function loadArray($array) {
		$this->_batchSize = $array['Paginator_batchSize'];
		$this->_pages = $array['Paginator_pages'];
		return $this;
	}

	public function getPages () {
		
		if (empty($this->_pages)){
			$repo = Container::instance("Repository");
			$data = $repo->retrieveBasicObjects($this->_access->informTotal()); 
			$this->_pages = $data->total / $this->getBatchSize();
		}
		return $this->_pages;
	}
	
	
	protected function _persistOnSession() {
		Session::set($this->_sessionName, $this);
	}
	
	public function render() {		
		$this->_persistOnSession();
		$retorno = array();
		$pages = $this->getPages();
		
		for ($i = 1; $i <= $this->getPages(); $i++){
			$retorno [] = Link::Link($i, "?page_id=$i")->__toString();
		}
		
		return implode(" | ", $retorno) . "<br/>";
	}
}