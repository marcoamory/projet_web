<?php

class TeacherController{
	
	private $_db;

	function __construct($db){
		$this->_db = $db;
	}

	function run(){

	echo "Page prof";

	if(isset($_SESSION['responsibility'])) echo 'isset';
	if(!empty($_SESSION['responsibility'])) echo 'notEmpty';
	echo $_SESSION['responsibility'];
	}
}