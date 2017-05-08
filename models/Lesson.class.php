<?php
class Lesson{

	private $_number;
	private $_bloc;

	public function __construct($number, $bloc){
		$this->_number = $number;
		$this->_bloc = $bloc;
	}

	public function get_number(){
		return $this->_number;
	}

	public function get_bloc(){
		return $this->_bloc;
	}
}