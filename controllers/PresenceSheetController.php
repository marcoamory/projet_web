<?php

class PresenceSheetController{
	
	private $_db;

	function __construct($db){

		$this->_db = $db;
	}

	function run(){

		if(isset($_POST['bloc'])){
		$bloc = htmlspecialchars($_POST['bloc']);
		$current_week = $this->select_current_week();
		$current_week_name = $current_week->name;
		$current_week_number = $current_week->week_number;
		$current_quadri = $current_week->quadri;
		$series = $this->select_serie_for_bloc($bloc);
		
		}

		if(isset($_POST['serie'])){
			$serie = htmlspecialchars($_POST['serie']);
			$students = $this->select_student_serie($serie, $bloc);
			$sessions = $this->select_session_serie($bloc, $serie, $current_quadri);
			
		}
	
	require_once(PATH_VIEW . 'presenceSheet.php');

	}

	//Select series numbers for that $bloc 
	private function select_serie_for_bloc($bloc){
		return $this->_db->select_serie_bloc($bloc);
	}
	
	//Select all students present in this $serie and $bloc
	private function select_student_serie($serie, $bloc){
		return $this->_db->select_student_serie($serie, $bloc);
	}

	private function select_current_week(){
		return $this->_db->select_current_week();
		
	}
	private function select_session_serie($bloc, $serie, $quadri){
		return $this->_db->select_session_serie($bloc, $serie, $quadri);
	}
}