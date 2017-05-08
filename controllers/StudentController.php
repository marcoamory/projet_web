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
			$session_array=$this->_db->select_star_session_lesson($_POST['lesson_fk']);
			$presence_array=array();
			for($i=0;$i<count($session_array);$i++){
				$presence_array[$i]=$this->_db->select_presences_student_session($_SESSION['email'],$session_array[$i]->id_session);
			}
			$_SESSION['lesson_chosen']=$this->_db->select_lesson_pk($_POST['lesson_fk']);
		}
		require_once(PATH_VIEW.'student.php');
	}
}