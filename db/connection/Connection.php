<?php

interface Connection {
	
	public function useServer($server);
	public function usePort($port);
	public function useUsername($username);
	public function usePassword($password);
	public function useDBNAme($dbname);
	
	
	public function connect ();
	public function close ();
	
	
	public function query($query,$limit = '');
	public function execute($sql);
	public function describe($table);
	
	
	public function getLastId();
	
	
	
	public function begin();
	public function rollback();
	public function commit();
	
	public function _init();
	public function _depose();
}