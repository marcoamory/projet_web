<?php

class AdminManagementController{
	
	private $_db;

	function __construct($db){

		$this->_db = $db;
	}
	function run(){

		$this->process_file('professor_csv');
		$this->process_file('agenda_properties');
		require_once(PATH_VIEW.'adminManagement.php');

	}
	
	public function wipe_data(){
		$this->_db->drop_all_data();
	}

	public function process_file($uploadName){
		if(isset($_FILES[$uploadName])){
			$tmp_name=$_FILES[$uploadName]['tmp_name'];
			$name=$_FILES[$uploadName]['name'];
			move_uploaded_file($tmp_name, PATH_CONF.$name);
			$this->file_to_DB($uploadName,$name);
		}
		if(!isset($_FILES['professor_csv'])&&!isset($_POST['agenda_properties'])&&isset($_POST['wipeChoice'])){
			$this->wipe_data();
		}
	}

	public function file_to_DB($uploadName,$name){
		if($uploadName=='professor_csv')
			$pattern = "(.*);(.*);(.*);(.*)\n$";
		else if ($uploadName=='lessons_csv')
			$pattern = "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
		$arrayFile = file(PATH_CONF . $name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
			if($uploadName=='professor_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					var_dump($groups);
					$this->_db->insert_teacher($groups[1], $groups[2], $groups[3], $groups[4]);
			}
			else if ($uploadName=='agenda_properties'){
				if(preg_match("/".$pattern."/", $line, $groups))
					$this->_db->insert_week($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
			}
		}

	}
}