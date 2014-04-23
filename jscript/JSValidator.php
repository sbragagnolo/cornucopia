<?php


interface JSValidator {

	/*
	 * getJsCall puede modificar el componente para que se dibuje agregando clases 
	 * (como proponen algunos frameworks de validacion) o bien retornar una instruccion jscript 
	 * que sera invocada a traves de una funcion js en 'onSubmit' del form a validar.
	 * */
	public function jsValidate($component);
}

