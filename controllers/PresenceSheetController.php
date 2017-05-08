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

		if(isset($_POST['session'])){
			$session = htmlspecialchars($_POST['session']);
			$week = $this->select_week_quadri($current_quadri);
			$sheet = array();
			foreach($week as $element){
				$sheet[] = $this->select_presence_sheet_week_sesssion($session, $element->week_number);

			}
			$presence_array = array();
			for($i=0; $i<count($sheet); $i++){
				if($sheet[$i]){
					$presence_array[$i] = $this->select_presence_presence_sheet($sheet[$i]->id_sheet);
					var_dump($presence_array[$i]);
					echo count($presence_array[$i]); 
				}
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

	private function select_presence_sheet_week_sesssion($id_session, $week_number){
		return $this->_db->select_presence_sheet_session_week($id_session, $week_number);
	}

	private function select_presence_presence_sheet($id_sheet){
		return $this->_db->select_presence($id_sheet);
	}
}