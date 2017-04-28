<?php

class LoginController{
	
	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){

		if(!isset($_POST['emailLogin']) OR empty($_POST['emailLogin']))
		{
			require_once(PATH_VIEW . "login.php");
		}

		else
		{
			$emailLogin = htmlspecialchars($_POST['emailLogin']);


			//Searching email in students table
			$studentSearch = $this->_db->search_student($emailLogin);

			if(!empty($studentSearch)){
				$student = new Student($studentSearch->email_student, $studentSearch->first_name, $studentSearch->last_name, $studentSearch->bloc, $studentSearch->number_serie);

				$_SESSION['email'] = $student->getEmail();
				$_SESSION['first_name'] = $student->getFirstName();
				$_SESSION['last_name'] = $student->getLastName();
				$_SESSION['type'] = "student";
				$_SESSION['authentifie'] = true;

				if(isset($_POST['rememberMe'])){
					setcookie('email', $student->getEmail(), time() + 365*24*3600, null, null, false, true);
					setcookie('first_name', $student->getFirstName(), time() + 365*24*3600, null, null, false, true);
					setcookie('last_name', $student->getLastName(), time() + 365*24*3600, null, null, false, true);
					setcookie('type', "student", time() + 365*24*3600, null, null, false, true);
				}

				header("Location:index.php?action=student");
				die();
			}

			else
			{
				//Searching email in teachers table
				$teacherSearch = $this->_db->search_teacher($emailLogin);

				if(!empty($teacherSearch)){
					$teacher = new Teacher($teacherSearch->email_teacher, $teacherSearch->first_name, $teacherSearch->last_name, $teacherSearch->responsibility);

					$_SESSION['email'] = $teacher->getEmail();
					$_SESSION['first_name'] = $teacher->getFirstName();
					$_SESSION['last_name'] = $teacher->getLastName();
					$_SESSION['responsibility'] = $teacher->getResponsibility();
					$_SESSION['type'] = 'teacher';
					$_SESSION['authentifie'] = true;

					if(isset($_POST['rememberMe'])){
						setcookie('email', $teacher->getEmail(), time() + 365*24*3600, null, null, false, true);
						setcookie('first_name', $teacher->getFirstName(), time() + 365*24*3600, null, null, false, true);
						setcookie('last_name', $teacher->getLastName(), time() + 365*24*3600, null, null, false, true);
						setcookie('responsibility', $teacher->getResponsibility(), time() + 365*24*3600, null, null, false, true);
						setcookie('type', "teacher", time() + 365*24*3600, null, null, false, true);
					}

					header("Location:index.php?action=teacher");
					die();
				}

				
			}
		}
		
	}
}