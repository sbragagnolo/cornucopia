<?php
include_once "html/components/HTMLContainer.php";

class BaseController extends Component {
	protected $page;
	
	public function __construct(){
		$this->page = new Page();
		$this->_init();
	}
	public function add($component){
		$this->page->add($component);
	}
	public function dispatch($selector){
		return $this->$selector();
	}
	public function getPage() {
		return $this->page;
	}
}

