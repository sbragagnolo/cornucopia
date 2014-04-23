<?php


include_once ("Component.php");

class HTMLList extends Component {
	protected $_items;
	
	public function __construct($items = array()){
		parent::__construct();
		$this->_items = $items;
	}
	
	public function render() {
		
		$list = "<ul ";
		
		foreach ($this->_data as $k=>$v){
			$list .= $k .'="' .$v.'"';
		}
		foreach ($this->untaged() as $value){
			$list .= " $value "; 
		}
		
		$list ." >";
		
		foreach ($this->_items as $item){
			if (is_array($item)) {
				$name = $item["name"];
				$label = $item["label"];
				$value = $item["value"];
				$list .= '
				<label for="'.$name.'">'. $label . ' </label> 
				<li name="'.$name.'"> '. $value .'" </li>
				';
			}else {
				$list .= '
					<li>'.$value.'</li>
				';
			}
		}
		return $list. $this->getErrors();;
	}
}