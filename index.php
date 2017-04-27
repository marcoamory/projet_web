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

	if(isset($_GET['action']) AND $_GET['action'] == 'login')
	{
		require_once(PATH_CONTROLLER . "loginController.php");
		$controller = new LoginController($db);
		$controller->run();
	}

	else if (empty($_SESSION['authentifie'])) {
   		header("Location: index.php?action=login");
		die();
	}

	else
	{
		$action = (isset($_GET['action'])) ? htmlentities($_GET['action']) : 'default';

		switch($action) {
		case 'student':
			require_once(PATH_CONTROLLER . "StudentController.php");
			$controller = new StudentController();
			break;	
		case 'teacher':
			require_once(PATH_CONTROLLER . 'TeacherController.php');
			$controller = new TeacherController();
			break;
		case 'blocManagement':
			require_once(PATH_CONTROLLER . "BlocManagementController.php");
			$controller = new BlocManagementController($db);
			break;
		default : 
			break;
		}

		$controller->run();
	}


	require_once(PATH_VIEW . "footer.php");

