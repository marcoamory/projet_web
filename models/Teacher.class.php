<?php
class Teacher{
	
	private $_email;	
	private $_name;
	private $_last_name;
	private $_responsability;

	public function __construct($email, $name, $last_name, $responsability){
		$this->_email = $email;
		$this->_name = $name;
		$this->_last_name = $last_name;
		$this->_responsability = $responsability;
	}

	public function getEmail(){
		return $this->_email;
	}

	public function getName(){
		return $this->_name;
	}

	public function getLastName(){
		return $this->_last_name;
	}

	public function getResponsability(){
		return $this->_responsability;
	}

	public function setResponsability($responsability){
		if($responsability == "true" OR $responsability == "false" OR $responsability == "bloc1" OR $responsability == "bloc2" OR $responsability == "bloc3" OR $responsability == "blocs" )
		{
			$this->_responsability = $responsability;
		}
	}
}