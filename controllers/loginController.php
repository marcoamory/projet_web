<?php

class LoginController{
	
	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){
		//If no login has been input, we stay on login page
		if(!isset($_POST['email_login']) OR empty($_POST['email_login']))
		{
			require_once(PATH_VIEW . "login.php");
		}

		else
		{
			$email_login = htmlspecialchars($_POST['email_login']);


			//Searching email in students table
			$studentSearch = $this->_db->select_student_pk($email_login);
			
			if(!empty($studentSearch)){
				$student = new Student($result->email_student, $result->first_name, $result->last_name, $result->bloc, $result->serie_number);
				$_SESSION['email'] = $student->getEmail();
				$_SESSION['first_name'] = $student->getFirstName();
				$_SESSION['last_name'] = $student->getLastName();
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
				$teacher = $this->_db->select_teacher_pk($email_login);
				
				if(!empty($teacher)){

					$_SESSION['email'] = $teacher->getEmail();
					$_SESSION['first_name'] = $teacher->getFirstName();
					$_SESSION['last_name'] = $teacher->getLastName();
					$_SESSION['responsibility'] = $teacher->getResponsibility();
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
			//No match result, we send an error message
			header('Location:index.php?action=login&message=error');
			die();
		}
		
	}
}