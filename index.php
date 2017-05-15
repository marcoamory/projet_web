<?php
	session_start();
	define("PATH_VIEW", "views/");
	define("PATH_CONTROLLER", "controllers/");
	define("PATH_MODEL", "models/");
	define("PATH_CSS", PATH_VIEW . "css/");
	define("PATH_IMAGE", PATH_VIEW . "images/");
	define("PATH_JS", PATH_VIEW . "js/");
	define("PATH_CONF", PATH_MODEL . "conf/");
	//Automatisation de l'inclusion des classes de la couche modèle
	function loadClass($class) {
		require_once(PATH_MODEL . $class . '.class.php');
	}
	spl_autoload_register('loadClass');
	require_once(PATH_VIEW . "header.php");
	$db=Db::getInstance();
	if(isset($_GET['action']) AND $_GET['action'] == 'login')
	{
		require_once(PATH_CONTROLLER . "loginController.php");
		$controller = new LoginController($db);
		$controller->run();
	}
	//Login check & redirection
	else if (empty($_SESSION['authentifie'])) {
		//Cookie check
		if(isset($_COOKIE['email']) AND isset($_COOKIE['first_name']) AND isset($_COOKIE['last_name']) AND isset($_COOKIE['type'])){
			$_SESSION['email'] = $_COOKIE['email'];
			$_SESSION['first_name'] = $_COOKIE['first_name'];
			$_SESSION['last_name'] = $_COOKIE['last_name'];
			$_SESSION['type'] = $_COOKIE['type'];
			$_SESSION['authentifie'] = true;

			if(isset($_COOKIE['responsibility'])){ 
				$_SESSION['responsibility'] = $_COOKIE['responsibility'];
				header("Location:index.php?action=teacher"); //Redirection for teachers' users
				die();
			}
			else{
				header("Location:index.php?action=student"); // Redirection for students'users
				die();
			}
		}
		else{
			header("Location: index.php?action=login"); // If there aren't any cookie, redirection to login's page
			die();
		}
	}
	else
	{
		//Check if there is a get's parameter. If there isn't, $action value is set at defaults
		$action = (isset($_GET['action'])) ? htmlentities($_GET['action']) : 'default';

		switch($action) {
		case 'student':
			require_once(PATH_CONTROLLER . "StudentController.php");
			$controller = new StudentController($db);
			break;	
		case 'teacher':
			require_once(PATH_CONTROLLER . 'TeacherController.php');
			$controller = new TeacherController($db);
			
			break;
		case 'blocManager':
		//Check if the teacher has the right responsibility
		if($_SESSION['responsibility'] != "bloc1" && $_SESSION['responsibility'] != "bloc2" && $_SESSION['responsibility'] != "bloc3"){
				header('Location:index.php?action=teacher');
				die();
			}
		else{
			require_once(PATH_CONTROLLER . "BlocManagerController.php");
			$controller = new BlocManagerController($db);
			
		}
			break;
		case 'blocsManager':
		//Check if the teacher has the right responsibility
		if($_SESSION['responsibility'] != "blocs"){
				header('Location:index.php?action=teacher');
				die();
			}
		else{
			require_once(PATH_CONTROLLER . "BlocsManagerController.php");
			$controller = new BlocsManagerController($db);
			
		}
			
			break;
		case 'adminManagement' :
		//Check if the teacher has the right responsibility
			if($_SESSION['responsibility'] != "true"){
				header('Location:index.php?action=teacher');
				die();
			}
			else{
				require_once(PATH_CONTROLLER . "AdminManagementController.php");
				$controller = new AdminManagementController($db);
				
			}
			
			break;
		case "presenceSheet" :
			if(isset($_SESSION['responsibility'])){
				require_once(PATH_CONTROLLER . "PresenceSheetController.php");
				$controller = new PresenceSheetController($db);
				
			}
			else{
				header("Location:index.php?action=student");
				die();
			}
			break;
		case 'logout' :
			require_once(PATH_CONTROLLER . "LogoutController.php");
			$controller = new LogoutController();
			break;
		default :
			if(isset($_SESSION['responsibility']))
			{
				require_once(PATH_CONTROLLER . "TeacherController.php");
				$controller = new TeacherController($db);
				
			}
			else
			{
				require_once(PATH_CONTROLLER . "StudentController.php");
				$controller = new StudentController($db);
			}
			break;
		}

		$controller->run();
	}
	require_once(PATH_VIEW . "footer.php");
?>
