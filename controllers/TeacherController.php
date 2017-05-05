<?php

class TeacherController{
	
	private $_db;

	function __construct($db){
		$this->_db = $db;
	}

	function run(){

	if(isset($_POST['bloc'])){
		$bloc = htmlspecialchars($_POST['bloc']);
		$series = select_serie_for_bloc($bloc);
	}
	
	require_once(PATH_VIEW . 'teacher.php');


	}
	private function select_serie_for_bloc($bloc){
		return $this->_db->select_serie_bloc($bloc);
	}
	
	private function select_session_for_bloc($bloc){

	}
}