<?php

class TeacherController{
	
	private $_db;

	function __construct($db){
		$this->_db = $db;
	}

	function run(){

	
	require_once(PATH_VIEW . 'teacher.php');
	}
}