<?php
include_once ("Component.php");

class Link extends Component{
	protected $_contain;
	public static function Link ($contain, $href="#", $target="_self"){
		return new Link($contain, $href, $target);
	}
	public function __construct($contain, $href="#", $target="_self"){
		$this->href = $href;
		$this->target = $target;
		$this->_contain = $contain;
	}
	
	public function render () {
		
		$return = "<a ";
		foreach ($this->_data as $k=>$v){
			$return .= $k .'="' .$v.'" ';
		}
		return $return . ">" . $this->_contain . "</a>";
		
	}
}
