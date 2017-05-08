<?php

class StudentController{

	private $_db;
	
	function __construct($db){
		$this->_db = $db;
	}

	function run(){
		$week_array=$this->_db->select_star_week();
		require_once(PATH_VIEW.'student.php');
	}
}