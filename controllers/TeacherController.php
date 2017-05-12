<?php

class TeacherController{
	
	private $_db;

	function __construct($db){
		$this->_db = $db;
	}

	function run(){

	if(isset($message)) unset($message);
	if(isset($message_warning)) unset($message_warning);

	if(isset($_POST['bloc'])){
		$bloc = htmlspecialchars($_POST['bloc']);
		$current_week = $this->select_current_week();
		if(!empty($current_week)){
			$current_week_name = $current_week->name;
			$current_week_number = $current_week->week_number;
			$current_quadri = $current_week->quadri;
		}
		
		if(!empty($this->select_student_bloc($bloc))){
			$series = $this->select_serie_for_bloc($bloc);
		}
		else{
			$message_warning = "Il n'y a aucun étudiant présent dans ce bloc !";
		}
		
	}

	if(isset($_POST['serie'])){
		$serie = htmlspecialchars($_POST['serie']);
		$students = $this->select_student_serie($serie, $bloc);
		$sessions = $this->select_session_serie($bloc, $serie, $current_quadri);

	}

	if(isset($_POST['session'])){
		$session = htmlspecialchars($_POST['session']);
		$presence_type_default;
		foreach ($sessions as $element) {
			if($element->id_session == $session){
				$presence_type_default = $element->presence_type;
				break;
			}
		}
		if(isset($_POST['presence_type'])){
			$presence_type = htmlspecialchars($_POST['presence_type']);
		}
		else{
			$presence_type = $presence_type_default;
		}

		if(isset($_POST['week']) AND !empty($_POST['week'])){
			$week = htmlspecialchars($_POST['week']);
			$week = $this->select_week_pk($week);
			$week_name = $week->name;
			$week_number = $week->week_number;
			$quadri = $week->quadri;
			$presence_sheet = $this->select_presence_sheet($_SESSION['email'], $session, $week_number);
		}
		else{
			$presence_sheet = $this->select_presence_sheet($_SESSION['email'], $session, $current_week_number);
		}
		
		$modify_presence_sheet = false;
		if(!empty($presence_sheet)){
			$modify_presence_sheet = true;
		}


	}
	
	if(isset($_POST['presence_send'])){
		if(empty($presence_sheet)){
			$this->create_presence_sheet($_SESSION['email'], $session, $week_number, $presence_type);
			$presence_sheet = $this->select_presence_sheet($_SESSION['email'], $session, $week_number);
		}
		for($i = 0; $i < count($students); $i++){
			if(isset($_POST['note' . $i])){
				$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), "present", htmlspecialchars($_POST['note' . $i]));
			}
			elseif(isset($_POST['presence' . $i])){
				$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), htmlspecialchars($_POST['presence' . $i]), NULL);
			}
		}

		$message = "Les présences ont correctement été insérées";

	}

	if(isset($_POST['modify_presence'])){
		for($i = 0; $i < count($students); $i++){
			if(isset($_POST['note' . $i])){
				$this->update_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), "present", htmlspecialchars($_POST['note' . $i]));
			}
			elseif(isset($_POST['presence' . $i])){
				$this->update_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), htmlspecialchars($_POST['presence' . $i]), NULL);
			}
		}
		$message = "Les présences ont correctement été modifiées";
	}
	
	require_once(PATH_VIEW . 'teacher.php');

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

	private function select_week_pk($week_number){
		return $this->_db->select_week_pk($week_number);
	}

	private function select_session_serie($bloc, $serie, $quadri){
		return $this->_db->select_session_serie($bloc, $serie, $quadri);
	}

	private function create_presence_sheet($email_theacher, $id_session, $week_number, $presence_type){
		$this->_db->insert_presence_sheet($email_theacher, $id_session, $week_number, $presence_type);
	}

	private function select_presence_sheet($email_theacher, $id_session, $week_number){
		return $this->_db->select_presence_sheet($email_theacher, $id_session, $week_number);
	}

	private function insert_presence($id_sheet, $email_student, $state, $grade){
		$this->_db->insert_presence($id_sheet, $email_student, $state, $grade);
	}

	private function update_presence($id_sheet, $email_student, $state, $grade){
		$this->_db->update_presence($id_sheet, $email_student, $state, $grade);
	}

	private function select_student_bloc($bloc){
		return $this->_db->select_student_bloc($bloc);
	}


}