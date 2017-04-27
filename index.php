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

	if(isset($_GET['action']) AND $_GET['action'] == 'login')
	{
		require_once(PATH_CONTROLLER . "loginController.php");
		$controller = new LoginController();
		$controller->run();
	}


require_once(PATH_VIEW . "header.php");


require_once(PATH_VIEW . "footer.php");