<?php
	session_start();
	define("PATH_VIEW", "views/");
	define("PATH_CONTROLLER", "controllers/");
	define("PATH_MODEL", "models/");
	define("PATH_CSS", PATH_VIEW . "css/");
	define("PATH_IMAGE", PATH_VIEW . "images/");
	define("PATH_JS", PATH_VIEW . "js/");
	define("PATH_CONF", "conf/");
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
			$controller = new StudentController($db);
			break;	
		case 'teacher':
			require_once(PATH_CONTROLLER . 'TeacherController.php');
			$controller = new TeacherController($db);
			break;
		case 'blocManager':
			require_once(PATH_CONTROLLER . "BlocManagerController.php");
			$controller = new BlocManagerController($db);
			break;
		case 'blocManagers':
			require_once(PATH_CONTROLLER . "BlocManagersController.php");
			$controller = new BlocManagersController($db);
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
?>
