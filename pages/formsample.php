<?php
include_once ("../html/components/Form.php");
include_once ("../html/constructors/FormBuilder.php");
include_once ("../html/components/CriteriaForm.php");
include_once ("../html/components/Input.php");
include_once ("../html/components/Custom.php");
include_once ("../html/components/Layout.php");
include_once ("../html/components/Page.php");

include_once ("../html/validators/Required.php");
include_once ("../html/validators/Type.php");
include_once ("../html/validators/Len.php");

include_once "../dependency/Container.php";
include_once "../db/repository/criteria/From.php";
include_once "../db/repository/criteria/Compare.php";
include_once "../db/repository/criteria/Like.php";

Container::register ("Persona", "Persona", "Persona.php", Container::Common());
Container::register ("Connection", "MySql", "../db/connection/MySql.php", Container::Singleton());
Container::register ("Repository", "Repository", "../db/repository/Repository.php", Container::Singleton());
//Container::register ("Paginator", "PersistentPaginator", "../paginator/PersistentPaginator.php", Container::Common(), array(function($paginator){ $paginator->setBatchSize(2); $paginator->tryToLoadSession();}));

session_start();
include_once 'Persona.php';
 
$conexion = Container::instance("Connection");
$conexion->useUsername("root")
		 ->usePassword("")
		 ->useDBName("prueba")
		 ->connect();

		 

$repository = Container::instance("Repository");




$form = FormBuilder::FormBuilder("Persona", "post");


/*
$cform = new CriteriaForm("get", "Persona");

$cform->add(Input::Text("nombre", ""), Like::LikeContains("nombre", ""))
	  ->add(Input::Text("dni", ""), Like::LikeContains("dni", ""))
	  ->add(Input::Text("ID", ""), Compare::equals("id", ""))
	  ->add(Input::Submit("submit", "Buscar!"));



if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	$cform->loadGet();	
	
}

echo $cform;
echo $cform->getCriteria();
$paginator = Container::instance("Paginator", $cform->getCriteria()); 

$lista = $paginator->retrieve();

echo $paginator;

$lista->apply(function ($persona) {
	
	 echo "Nombre: " . $persona->nombre . "<br/>"
		     ."Apellido: " . $persona->dni . "<br/>"
		     ."Dni: " . $persona->nacimiento . "<br/>"
		     ."ID: " . $persona->id . "<br/><br/><br/>";
	});

echo $paginator;


*/

$form = new Form("post", "Persona");
$form->add(Input::Text("nombre", "Nombre")
		->addValidator(new Required()))
	 ->add(Input::Text("apellido", "Apellido")
	 	->addValidator(new Required())
	 	->addValidator(new Len(10)))
	 ->add(Input::Text("dni", "DNI")
	 	->addValidator(new Required())
	 	->addValidator(new Numeric()))
	 ->add(Input::Text("id", "ID")
	 	->addValidator(new Numeric()))
	 ->add(Input::Submit("submit", "guardar"))
	 ->useTemplate("sarasa.php");
	
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$form->loadPost();
	if ($form->isValid()){
		$persona = $form->loadObject();
		$persona = $repository->save($persona);
	}
	else{
		echo "El formulario es invalido. Por favor verifique los errores.";
	}
}



$layout = new Layout("Titulo");
$layout->addToHeader(Custom::Custom('<script type="text/javascript" src="http://localhost/cornucopia/jquery-validate/jquery.validate.js"></script>'));
$page = new Page("Alta Personas");
$page->add($form);

$layout->usePage($page);
echo $layout;

