<?php

$selector = Request::get("case_selector");


$controller = Container::instance($selector."Controller");


try {
	$use = Request::get("use_selector");
	if ($controller->dispatch($use)) {
		$layout = Container::instance("Layout");
	}
	else{
		$layout = Container::instance("ErrorLayout");
	}
	
	$layout->usePage($controller->getPage());
}
catch(Exception $e){
	$layout = Container::instance("ErrorLayout");
	$page = new Page();
	$page->add(Custom::Custom("<span> Error! selector:: $selector <br/> use:: $use <br/> " . $e->getMessage()));
	$layout->usePage($page);
}


echo $layout;

