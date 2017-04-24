<?php
session_start();

define("PATH_VIEW", "views/");
define("PATH_CONTROLLER", "controllers/");
define("PATH_MODEL", "models/");
define("PATH_CSS", PATH_VIEW . "css/");
define("PATH_IMAGE", PATH_VIEW . "images/");

//Automatisation de l'inclusion des classes de la couche modèle
	function loadClass($class) {
		require_once(PATH_MODEL . $class . '.class.php');
	}
	spl_autoload_register('loadClass');

if (empty($_SESSION['authentifie'])) {
   header("Location: index.php?action=login");
	die();
}