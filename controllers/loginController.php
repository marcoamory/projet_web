<?php

class loginController{
	
	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){

		if(!isset($_POST['emailLogin']))
		{
			require_once(PATH_VIEW . "login.php");
		}

		else
		{
			$emailLogin = htmlspecialchars($_POST['emailLogin']);

			$studentSearch = $this->_db->searchStudent($emailLogin);

			if(!empty($studentSearch)){
				$student = new Student($studentSearch->email_student, $studentSearch->name, $studentSearch->last_name, $studentSearch->bloc, $studentSearch->number);

				$_SESSION['email'] = $student->getEmail();
				$_SESSION['name'] = $student->getName();
				$_SESSION['last_name'] = $student->getLastName();
				$_SESSION['authentifie'] = true;

				header("Location:index.php?action=student");
				die();
			}

			else
			{
				$teacherSearch = $this->_db->searchTeacher($emailLogin);

				if(!empty($teacherSearch)){
					$teacher = new Teacher($teacherSearch->email_teacher, $teacherSearch->name, $teacherSearch->last_name, $teacherSearch->responsability);

					$_SESSION['email'] = $teacher->getEmail();
					$_SESSION['name'] = $teacher->getName();
					$_SESSION['last_name'] = $teacher->getLastName();
					$_SESSION['responsability'] = $teacher->getResponsability();
					$_SESSION['authentifie'] = true;

					header("Location:index.php?action=teacher");
					die();
				}

				
			}
		}
		
	}
}