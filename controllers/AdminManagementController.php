<?php

class AdminManagementController{
	
	private $_db;

	function __construct($db){

		$this->_db = $db;
	}

	function run(){

		public function process_file($uploadName){
			if(isset($_FILES[$uploadName])){
				$tmp_name=$_FILES[$uploadName]['tmp_name'];
				$name=$_FILES[$uploadName]['name'];
				move_uploaded_file($tmp_name, PATH_CONF.$name);
				$this->file_to_DB($uploadName,$name);
			}
		}

		public function file_to_DB($uploadName,$name){
			if($uploadName=='students_csv')
				$pattern = "(.*);(.*);(.*);(.*)\n$";
			else if ($uploadName=='lessons_csv')
				$pattern = "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
			$arrayFile = file(PATH_CONF . $name);
			$nb_lines = count($arrayFile);
			for($i = 1; $i < $nb_lines; $i++){
				$line = $arrayFile[$i];	
				if($uploadName=='students_csv'){
					if(preg_match("/".$pattern."/", $line, $groups))
						var_dump($groups);
						$this->_db->insert_student($groups[1], $groups[2], $groups[3], $groups[4]);
				}
				else if ($uploadName=='lessons_csv'){
					if(preg_match("/".$pattern."/", $line, $groups))
						$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
				}
			}

		}

	}
}