<?php

class StudentController{

	private $_db;
	
	function __construct($db){
		$this->_db = $db;
	}

	function run(){
	
		echo "Page Student";
	}
}