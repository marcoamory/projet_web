<?php

class LogoutController{
	
	function __construct(){

	}

	function run(){

		$_SESSION = array(); //Suppression session		
		session_destroy();

		header("Location: index.php?action=login");
		die();


	}
}