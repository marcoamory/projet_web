<?php

class StudentController{

	private $_db;
	
	function __construct($db){
		$this->_db = $db;
	}

	function run(){
		$lesson_array=$this->_db->select_lesson_name_and_lesson_code("I".substr($_SESSION['bloc'],4,5));
		$week_array=$this->_db->select_star_week();
		if(isset($_POST['lesson_fk'])){
			$presence_array=$this->_db->select_star_presence_student($_SESSION['email']);
			var_dump($presence_array);
			$_SESSION['lesson_chosen']=$this->_db->select_lesson_pk($_POST['lesson_fk']);
		}
		require_once(PATH_VIEW.'student.php');
	}
}