<?php
	session_start();
	
	define("PATH_VIEW", "views/");
	define("PATH_CONTROLLER", "controllers/");
	define("PATH_MODEL", "models/");
	define("PATH_CSS", PATH_VIEW . "css/");
	define("PATH_IMAGE", PATH_VIEW . "images/");
	define("PATH_JS", PATH_VIEW . "js/");
	
	//Automatisation de l'inclusion des classes de la couche modèle
	function loadClass($class) {
		require_once(PATH_MODEL . $class . '.class.php');
	}
	spl_autoload_register('loadClass');

	require_once(PATH_VIEW . "header.php");

	$db=Db::getInstance();

	//Login page include
	if(isset($_GET['action']) AND $_GET['action'] == 'login')
	{
		require_once(PATH_CONTROLLER . "loginController.php");
		$controller = new LoginController($db);
		$controller->run();
	}
	//Login check & redirection
	else if (empty($_SESSION['authentifie'])) {
		//Cookie check
		if(isset($_COOKIE['email']) AND isset($_COOKIE['name']) AND isset($_COOKIE['last_name']) AND isset($_COOKIE['type'])){
			$_SESSION['email'] = $_COOKIE['email'];
			$_SESSION['name'] = $_COOKIE['name'];
			$_SESSION['last_name'] = $_COOKIE['last_name'];
			$_SESSION['type'] = $_COOKIE['type'];
			$_SESSION['authentifie'] = true;

			if(isset($_COOKIE['responsibility'])){
				$_SESSION['responsibility'] = $_COOKIE['responsibility'];
				header("Location:index.php?action=teacher");
				die();
			}
			else{
				header("Location:index.php?action=student");
				die();
			}
		}
		else{
			header("Location: index.php?action=login");
			die();
		}
	}

	else
	{
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
		case 'blocManagement':
			require_once(PATH_CONTROLLER . "BlocManagementController.php");
			$controller = new BlocManagementController($db);
			break;
		case 'adminManagement' :
			require_once(PATH_CONTROLLER . "AdminManagementController.php");
			$controller = new AdminManagementController($db);
			break;
		case 'logout' :
			require_once(PATH_CONTROLLER . "LogoutController.php");
			$controller = new LogoutController();
			break;
		default :
			if(isset($_SESSION['responsability']))
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

