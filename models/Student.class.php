<?php

class Student{
	
	private $_email;
	private $_first_name;
	private $_last_name;
	private $_bloc;
	private $_serie_number;

	public function __construct($email, $first_name, $last_name, $bloc, $serie_number){
		$this->_email = $email;
		$this->_first_name = $first_name;
		$this->_last_name = $last_name;
		$this->_bloc = $bloc;
		$this->_serie_number = $serie_number;		
	}

	public function getEmail(){
		return $this->_email;
	}

	public function getFirstName(){
		return $this->_first_name;
	}

	public function getLastName(){
		return $this->_last_name;
	}

	public function getBloc(){
		return $this->_bloc;
	}

	public function getSerie(){
		return $this->_serie_number;
	}
}