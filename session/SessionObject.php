<?php
interface SessionObject {
	public function toArray();
	public function loadArray($array);
}