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

		if(isset($_POST['justify'])){
			$justify = htmlspecialchars($_POST['justify']);
			$justify_explode = explode("+", $justify);
			$this->update_presence_to_justify($justify_explode[1], $justify_explode[0]);

		}

		if(isset($_POST['session'])){
			$session = htmlspecialchars($_POST['session']);
			$week = $this->select_week_quadri($current_quadri);
			$students_array = array();
			foreach($students as $element){
				$students_array[] = $this->select_presence_student($element->getEmail(), $session);
			}	
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

	private function select_week_quadri($quadri){
		return $this->_db->select_week_quadri($quadri);
	}
	private function select_session_serie($bloc, $serie, $quadri){
		return $this->_db->select_session_serie($bloc, $serie, $quadri);
	}

	private function update_presence_to_justify($id_sheet, $email_student){
		return $this->_db->update_presence_to_justify($id_sheet, $email_student);
	}

	private function select_presence_student($email_student, $id_session){
		return $this->_db->select_presence_student($email_student, $id_session);
	}
}