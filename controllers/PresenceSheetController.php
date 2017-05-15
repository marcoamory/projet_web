<?php

class PresenceSheetController{
	
	private $_db;

	function __construct($db){

		$this->_db = $db;
	}

	function run(){

		//Unset notification's messages
		if(isset($message)) unset($message);
		if(isset($message_warning)) unset($message_warning);

		//If a bloc has been selected, we select the current week of the year. If it can find the current week, there is a warning message and the code dies.
		//Then we look into the data if there are any students in the selected bloc. If there aren't, there is a warning message. Else, we search the series present in this bloc. If there aren't any, there is a warning message.
		if(isset($_POST['bloc'])){
			$bloc = htmlspecialchars($_POST['bloc']);
			$current_week = $this->select_current_week();
			if(!empty($current_week)){
				$current_week_name = $current_week->name;
				$current_week_number = $current_week->week_number;
				$current_quadri = $current_week->quadri;
			}
			else{
				$message_warning = "La semaine actuelle n'est pas retrouvée dans l'agenda! Mettez le à jour pour continuer";
				require_once(PATH_VIEW . "presenceSheet.php");
				die();
			}
			
			if(!empty($this->select_student_bloc($bloc))){
				$series = $this->select_serie_for_bloc($bloc);
				if(empty($series)){
					$message_warning = "Il n'y a aucune série pour ce bloc";
				}
			}
			else{
				$message_warning = "Il n'y a aucun étudiant présent dans ce bloc !";
			}
		
		}
		//If a serie has been selected, we select the sessions relative to this bloc, serie and current quadri and all students 
		//If there aren't any sessions for the selected serie, there is a warning message
		if(isset($_POST['serie'])){
			$serie = htmlspecialchars($_POST['serie']);
			$students = $this->select_student_star();
			$sessions = $this->select_session_serie($bloc, $serie, $current_quadri);
			if(empty($sessions)){
				$message_warning = "Il n'y a pas de séance type pour cette série !";
			}
			
		}

		if(isset($_POST['justify'])){
			$justify = htmlspecialchars($_POST['justify']);
			$justify_explode = explode("+", $justify);
			$this->update_presence_to_justify($justify_explode[1], $justify_explode[0]);

		}

		if(isset($_POST['session'])){
			$session = htmlspecialchars($_POST['session']);
			foreach($sessions as $element){
				if($element->id_session == $session){
					$session_name = $element->name;
				}
			}
			$week = $this->select_week_quadri($current_quadri);
			$students_array = array();
			foreach($students as $element){
				$stud = $this->select_presence_student($element->getEmail(), $session_name);
				if(!empty($stud)){
					$students_array[] = $stud;
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
	private function select_student_star(){
		return $this->_db->select_student_star();
	}
	//Select the current week using the date system
	private function select_current_week(){
		return $this->_db->select_current_week();	
	}
	//Select all weeks of this $quadri
	private function select_week_quadri($quadri){
		return $this->_db->select_week_quadri($quadri);
	}
	//Select all sessions belonging to $bloc, $serie and $quadri 
	private function select_session_serie($bloc, $serie, $quadri){
		return $this->_db->select_session_serie($bloc, $serie, $quadri);
	}
	//Update the student's presence of $email_student in $id_sheet
	private function update_presence_to_justify($id_sheet, $email_student){
		return $this->_db->update_presence_to_justify($id_sheet, $email_student);
	}
	//Select all presences of this $email_student for this $id_session
	private function select_presence_student($email_student, $id_session){
		return $this->_db->select_presence_student($email_student, $id_session);
	}
	//Select all students existing in this bloc
	private function select_student_bloc($bloc){
		return $this->_db->select_student_bloc($bloc);
	}
}