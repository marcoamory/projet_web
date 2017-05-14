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
			$current_week_number = $current_week->week_number;
			$current_quadri = $current_week->quadri;
		}
		
		if(!empty($this->select_student_bloc($bloc))){
			$series = $this->select_serie_for_bloc($bloc);
			if(empty($series)){
				$message_warning = "Il n'y a aucune série pour ce bloc !";
			}
		}
		else{
			$message_warning = "Il n'y a aucun étudiant dans ce bloc !";
		}
		
	}

	if(isset($_POST['serie'])){
		$serie = htmlspecialchars($_POST['serie']);
		$students = $this->select_student_serie($serie, $bloc);
		if(empty($students)){
			$message_warning = "Il n'y a aucun étudiant dans cette série !";
		}
		if(isset($_POST['add_student_email'])){
			echo "add";
			$add_student_email = htmlspecialchars($_POST['add_student_email']);
			$add_student = $this->select_student_pk($add_student_email);
			$add_student_obj = new Student($add_student->email_student, $add_student->first_name, $add_student->last_name, $add_student->bloc, $add_student->serie_number);
			$students[] = $add_student_obj;
			var_dump($students);
		}
		$sessions = $this->select_session_serie($bloc, $serie, $current_quadri);
		if(empty($sessions)){
			$message_warning = "Il n'y a aucune séance type pour cette série !";
		}
	}

	if(isset($_POST['session'])){
		$session = htmlspecialchars($_POST['session']);
		$presence_type_default = 'X';
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
			$week = $this->select_week_pk($week, $current_quadri);
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

	if(isset($_POST['modify_presence'])){
		for($i = 0; $i < count($students); $i++){
			var_dump($students);
			$presence_session = $this->select_presences_student_session($students[$i]->getEmail(), $session);
			var_dump($presence_session);
			if(!empty($presence_session)){
				if(isset($_POST['note' . $i])){
					$this->update_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), "present", htmlspecialchars($_POST['note' . $i]));
				}
				elseif(isset($_POST['presence' . $i])){
					$this->update_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), htmlspecialchars($_POST['presence' . $i]), NULL);
				}
			}
			else{
				if(isset($_POST['note' . $i])){
					$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), "present", htmlspecialchars($_POST['note' . $i]));
				}
				elseif(isset($_POST['presence' . $i])){
					$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), htmlspecialchars($_POST['presence' . $i]), NULL);
				}
			
			}
		}
		$message = "Les présences ont correctement été modifiées";
	}
	//If the user sends presences, it first checks if there already is a presence_sheet for the selected week and session
	//If there isn't it creates a new one else it uses the existing one. Then if there is a grade, it automatically inserts "present" as state
	//And it inserts the grade, else it inserts the selected state and null as grade. Finally, it switches modify_presence_sheet's variable to true in order to
	//switch "Enregistrer" button to "Modifier" button  
	else if(isset($_POST['presence_send'])){
		if(empty($presence_sheet)){
			$this->create_presence_sheet($_SESSION['email'], $session, $week_number, $presence_type);
			$presence_sheet = $this->select_presence_sheet($_SESSION['email'], $session, $week_number);
		}
		for($i = 0; $i < count($students); $i++){
			var_dump($students);
			if(isset($_POST['note' . $i])){
				$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), "present", htmlspecialchars($_POST['note' . $i]));
			}
			elseif(isset($_POST['presence' . $i])){
				$this->insert_presence($presence_sheet->id_sheet, $students[$i]->getEmail(), htmlspecialchars($_POST['presence' . $i]), NULL);
			}
		}

		$message = "Les présences ont correctement été insérées";
		$modify_presence_sheet = true;

	}

	if(isset($_GET['message']) AND $_GET['message'] == 'add_student'){
		if(isset($_POST['add_student_name'])){
			$add_student_name = htmlspecialchars($_POST["add_student_name"]);
			$students_prospect = $this->select_student_like_last_name($add_student_name);
			if(empty($students_prospect)){
				$message_warning="Il n'y a aucun étudiant de ce nom";
			}
		}
		require_once(PATH_VIEW . "addStudent.php");
		die();
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
	//Select the current week using date system
	private function select_current_week(){
		return $this->_db->select_current_week();
		
	}
	//Select the week with $week_number as week_number and $quadri as quadri
	private function select_week_pk($week_number, $quadri){
		return $this->_db->select_week_pk($week_number, $quadri);
	}
	//Select all sessions existing for bloc n°$bloc, serie n°$serie and quadri $quadri
	private function select_session_serie($bloc, $serie, $quadri){
		return $this->_db->select_session_serie($bloc, $serie, $quadri);
	}
	//Insert $email_teacher, $id_session, $week_number, $presence_type INTO the presence_sheets table
	private function create_presence_sheet($email_theacher, $id_session, $week_number, $presence_type){
		$this->_db->insert_presence_sheet($email_theacher, $id_session, $week_number, $presence_type);
	}
	//Select the presence_sheets binded to $email_teacher, $id_session and $week_number
	private function select_presence_sheet($email_theacher, $id_session, $week_number){
		return $this->_db->select_presence_sheet($email_theacher, $id_session, $week_number);
	}
	//Insert into presences the id_sheet referencing to a presence_sheet, the email_student referencing to a student, the state and the grade
	private function insert_presence($id_sheet, $email_student, $state, $grade){
		$this->_db->insert_presence($id_sheet, $email_student, $state, $grade);
	}
	//Update the student's prensence searching in presences' table the concernated student and sheet
	private function update_presence($id_sheet, $email_student, $state, $grade){
		$this->_db->update_presence($id_sheet, $email_student, $state, $grade);
	}
	//Select all students present in this $bloc
	private function select_student_bloc($bloc){
		return $this->_db->select_student_bloc($bloc);
	}
	//Select all presences for a $student and a $session
	private function select_presences_student_session($email_student, $id_session){
		return $this->_db->select_presences_student_session($email_student, $id_session);
	}

	private function select_student_like_last_name($last_name){
		return $this->_db->select_student_like_last_name($last_name);
	}

	private function select_student_pk($email_student){
		return $this->_db->select_student_pk($email_student);
	}

}