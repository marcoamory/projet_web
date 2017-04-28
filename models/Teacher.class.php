<?php
class Teacher{
	
	private $_email;	
	private $_first_name;
	private $_last_name;
	private $_responsibility;

	public function __construct($email, $first_name, $last_name, $responsibility){
		$this->_email = $email;
		$this->_fist_name = $first_name;
		$this->_last_name = $last_name;
		$this->_responsibility = $responsibility;
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

	public function getResponsibility(){
		return $this->_responsibility;
	}

	public function setResponsibility($responsibility){
		if($responsibility == "true" OR $responsibility == "false" OR $responsibility == "bloc1" OR $responsibility == "bloc2" OR $responsibility == "bloc3" OR $responsibility == "blocs" )
		{
			$this->_responsibility = $responsibility;
		}
	}
}