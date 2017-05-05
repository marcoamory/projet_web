<?php

class TeacherController{
	
	private $_db;

	function __construct($db){
		$this->_db = $db;
	}

	function run(){

	if(isset($_POST['bloc'])){
		$bloc = htmlspecialchars($_POST['bloc']);
		$series = $this->select_serie_for_bloc($bloc);
		$lessons = $this->select_session_for_bloc($bloc);

	}

	if(isset($_POST['serie']) AND isset($_POST["lesson"])){
		$serie = htmlspecialchars($_POST['serie']);
		$lesson = htmlspecialchars($_POST['lesson']);
		$students = $this->select_student_serie($serie, $bloc);
	}
	
	require_once(PATH_VIEW . 'teacher.php');

	}

	//Select series numbers for that $bloc 
	private function select_serie_for_bloc($bloc){
		return $this->_db->select_serie_bloc($bloc);
	}
	
	//Select lessons name present for that bloc
	private function select_session_for_bloc($bloc){
		$bloc_number = substr($bloc, -1, 1);
		return $this->_db->select_lesson_bloc($bloc_number);
	}

	private function select_student_serie($serie, $bloc){
		return $this->_db->select_student_serie($serie, $bloc);
	}


}