<?php

class FormBuilder extends BasicObject {
	protected $_form;
	
	public function __construct(){
		
	}
	public static function FormBuilder ($class, $method="post", $perform=false, $action = "#"){
		$bul = new FormBuilder();
		return $bul->_formBuilder($class, $method, $perform, $action);
	}
	public static function CriteriaFormBuilder ($class, $method="post", $perform=false, $action = "#"){
		$bul = new FormBuilder();
		return $bul->_criteriaFormBuilder($class, $method, $perform, $action);
	}
	public function _formBuilder($class, $method="post", $perform=false, $action = "#") {
		$form = Form::Form($method,$class,$class);
		$repository = Container::instance("Repository");
		$form->action = $action;
		if($perform) {
			$form->actionToPerform($perform);
		}

		$proxy = $repository->getProxyPrototype($class);
		foreach($proxy->getAttributes() as $name => $attribute){
			$label = ucwords($name);
			$form->add(Custom::Custom ("<label>$label</label>"));
			$form->add($this->createComponent($name, $attribute, $proxy));
			$form->add(Custom::Custom("<br/>"));
		}
		$form->add(Input::Submit("submit", "Guardar"));
		return $form;
	}
	
	protected function _nativeType($type){
		$type = strtoupper($type);
		if (substr_count($type, "CHAR") > 0){
			return "string";
		}
		if (substr_count($type, "INT") > 0){
			return "int";
		}
		if (substr_count($type, "FLOAT") > 0 || substr_count($type, "DOUBLE") > 0 || substr_count($type, "REAL") > 0 || substr_count($type, "NUMERIC") > 0 || substr_count($type, "DECIMAL") > 0){
			return "float";
		}
		if (substr_count($type, "DATE") > 0 || substr_count($type, "TIME") > 0 || substr_count($type, "YEAR") > 0 ) {
			return "date";
		}
	}
	
	protected function _typeLen ($type){
		$arr = explode("(", $type);
		
		if (count ($arr) < 2) return 0;
		
		$arr = explode(")",$arr[1]);
		if (is_numeric($arr[0])) return $arr[0];
		
		throw new Program ("Error al calcular longitud del tipo. $type");
	}
	
	public function createComponent($name, $attribute,$proxy){
		$type = $attribute['Type'];
		$require = $attribute ['Require'];
		$nativeType = $this->_nativeType($type);
		
		if($proxy->_isFromOtherTable($name)){
			$component = Combo::Combo($name, $proxy->potencialValues($name));
		} 
		else{
			if($nativeType == "string"){
				$component = Input::Text($name,"");
				$component->addValidator(new Len($this->_typeLen($type)));
			}
			if($nativeType == "int"){
				$component = Input::Text($name,"0");
				$component->addValidator(new Numeric());
			}
			if($nativeType == "float"){
				$component = Input::Text($name,"0.00");
				$component->addValidator(new Numeric());
			}
			if($nativeType == "date"){
				$component = Input::Text($name,date("d-m-Y"));
				/** @todo: Validador DATE- Componente DATE*/
				$component->addValidator(new Date("d-m-Y"));
			}
		}
		if($require){
			$component->addValidator(new Required());
		}
		return $component;
	}
	
	public function createCriteria($name, $attribute,$proxy){
		$type = $attribute['Type'];
		$require = $attribute ['Require'];
		$nativeType = $this->_nativeType($type);
		
		if($nativeType == "string"){
			$criteria = Like::LikeContains($name,"");
		}
		else{
			$criteria = Compare::equals($name, 0);
		}

		return $criteria;
	}
	public function _criteriaFormBuilder($class, $method="post", $perform=false, $action = "#") {
		$form = CriteriaForm::Form($method,$class,$class);
		$repository = Container::instance("Repository");
		$form->action = $action;
		if($perform) {
			$form->actionToPerform($perform);
		}
		$proxy = $repository->getProxyPrototype($class);	
		
		foreach($proxy->getAttributes() as $name => $attribute){
			$form->add(Custom::Custom("<label>$name</label>"));
			$form->add($this->createComponent($name, $attribute,$proxy), 
					   $this->createCriteria($name, $attribute,$proxy));
			$form->add(Custom::Custom("<br/>"));
		}
		$form->add(Input::Submit("submit", "Buscar"));
		return $form;
	}
	/*
	 * @todo PODER ANOTAR A NIVEL DE CLASE UN ARRAY FORM-NAME => METODO A EJECUTAR
	 * 
	 * */
	
}
