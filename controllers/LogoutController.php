<?php

class LogoutController{
	
	function __construct(){

	}

	function run(){

		$_SESSION = array(); //Suppression session		
		session_destroy();

	require_once(PATH_VIEW . "logout.php");


	}
}