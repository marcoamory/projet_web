<?php

class LogoutController{
	
	function __construct(){

	}

	function run(){

		setcookie("email", "");
		setcookie("name", "");
		setcookie("last_name", "");
		setcookie("type", "");

		$_SESSION = array(); //Delete session		
		session_destroy();

		header("Location: index.php?action=login");
		die();


	}
}