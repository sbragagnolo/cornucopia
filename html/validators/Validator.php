<?php
interface Validator {
	public function validate(Component $component);
	public function jsValidate(Component $component);
	public function getErrorMsg(Component $component);
}