<?php


class Verifier {
	
	public static function acutes ($arg){
		if (is_numeric($arg)) return $arg;
		if ($arg == "NULL") return "NULL";
		return "'$arg'";
	}
	public static function values ($arg){
		if (!$arg) return "NULL";
		return $arg;
	}
	
	public static function verifyOperator ($op){
		
		$valids = array ("=","<", ">", "<=", ">=", "<>", "in", "not in", "between", "not between");
		foreach ($valids as $valid) {
			if($op == $valid) return $op;
		}
		throw new Program(" operador $op no pasa control de taboo: $valid ");
	}
	
	public static function verifySelect ($arg){
		$taboos = array ("delete","drop","insert","update", "create");

		$larg = strtolower($arg);
		foreach($taboos as $taboo){
			if(substr_count($larg, $taboo) > 0) {
				throw new Program(" argumento $arg no pasa control de taboo: $taboo ");
			}
		}
		return $arg;
	}
}


