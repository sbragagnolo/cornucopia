<?php
class Layout extends Component {
	protected $_header = array();
	protected $_footer = array();
	protected $page;
	protected $title;
	
	public function __construct($defaultTitle = "Title"){
		$this->title = $defaultTitle;
	}
	public function addToHeader($component){
		$this->_header[] = $component;
	}
	public function addToFooter($component){
		$this->_footer[] = $component;
	}
	
	public function getHeader() {
		return $this->_header + $this->page->getHeaderContainer();
	}
	public function getFooter(){
		return $this->_footer + $this->page->getFooterContainer();
	}
	public function usePage($page){
		$this->page = $page;
	}
	
	public function __get($k){
		if(array_key_exists($k,$this->_data)){
			return self::__get($k);
		}
		return $this->page->__get($k);
	}
	
	public function getTitle() {
		if($this->page && $this->page->getTitle() && $this->page->getTitle()!= "Title"){
			return $this->page->getTitle();
		}
		return $this->title;
	}
	public function headerContent(){
		$headers = $this->getHeader();	
		$header = "";
		foreach($headers as $component){
			
			$header .= " " . $component;
		}
		return $header;
	}
	public function footerContent(){
		$footers = $this->getFooter();	
		
		$footer = "";
		foreach($footers as $component){
			$footer .= " " . $component;
		}
		return $footer;
	}
	
	public function getPage(){
		if(!$this->page){
			throw new Program ("Se intenta mostrar una pagina vacia");
		}
		return $this->page;
	}
	
	public function render(){
		$header = $this->headerContent();
		$page = $this->getPage();
		$footer = $this->footerContent();	
		$title = $this->getTitle();
		
		return "<head>
					$header
					<title> $title </title> 
				<head>  
				<body>
					$page
				</body>
				$footer
				";
				 
	}
}