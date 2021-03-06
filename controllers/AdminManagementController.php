﻿<?php

class AdminManagementController{
	
	private $_db;

	function __construct($db){

		$this->_db = $db;
	}
	function run(){
		//Check all notification's variables, unset them
		if(isset($_SESSION['notification_error']))
			unset($_SESSION['notification_error']);
		if(isset($_SESSION['notification_success']))
			unset($_SESSION['notification_success']);
		if(isset($_SESSION['notification_warning'])){
			unset($_SESSION['notification_warning']);
		}
		
		//Drop all data from the data base if the button wipe_data has been clicked
		if(isset($_POST["wipe_data"])){
			$this->wipe_data();
			$_SESSION['notification_success'] = "Toutes les données ont été réinitialisées";
		}
		$this->process_file('professor_csv');
		$this->process_file('agenda_properties');


		require_once(PATH_VIEW.'adminManagement.php');

	}
	
	private function wipe_data(){
		$this->_db->drop_all_data();
	}

	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * this function defines a pattern and then moves the file being uploaded in the conf directory if is_compatible_file returned true
	 * and then calls for a data process on the database
	 * also defines a notification to inform the user of what happened
	 */
	private function process_file($uploadName){
		$pattern=$this->define_pattern($uploadName);
		if(isset($_FILES[$uploadName])){
			$tmp_name=$_FILES[$uploadName]['tmp_name'];
			$name=$_FILES[$uploadName]['name'];
			if($this->is_compatible_file($tmp_name,$name,$uploadName,$pattern)){
				move_uploaded_file($tmp_name, PATH_CONF.$name);
				$nbData=$this->file_to_DB($uploadName,$name,$pattern);
				if($nbData["duplicate"]==0)
					$_SESSION['notification_success']="Vos données ont bien été traitées [" . $nbData['insert'] . " donnée(s) ajoutée(s)]";
					else
						$_SESSION['notification_warning']="Vos données ont bien été traitées mais ". $nbData['duplicate'] ." donnée(s) étai(en)t déjà présente(s) [" . $nbData['insert'] ." donnée(s) ajoutée(s)]";
			}
			else{
				$_SESSION['notification_error']="Votre fichier n'est pas compatible";
			}
		}
	}
	/*
	 * $tmp_name is a string containing the absolute path of the temporary location it's being uploaded to on the server
	 * $name is a string containing the name of the file the user uploaded
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * this function checks if the file is a .csv or .properties, compatible with our database and our constraints
	 */
	private function is_compatible_file($tmp_name,$name ,$uploadName,$pattern){
		if(!preg_match("/^professeurs\.csv$/",$name)&&!preg_match("/^agenda\.properties$/",$name)){
			return false;
		}
		
		return true;
	}

	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * this function returns a pattern for an upcoming preg_match function depending on $uploadName
	 */
	private function define_pattern($uploadName){
		if($uploadName=='professor_csv')
			return "(.*);(.*);(.*);(.*)$";
		return "(.*)_(.*)=(.*)$";
	}

	/*
	 * $keyValue is a string that contains the value of the pk we are looking for
	 * this function return true if the data is being duplicated and false if it's not
	 */
	private function teacher_being_duplicated($keyValue){
		if(!$this->_db->select_teacher_pk($keyValue)){
			return false;
		}
		
		return true;
	}
	/*
	 * $keyValue1 and $keyValue2 are strings that contain the values of the two pk we are looking for
	 * this function return true if the data is being duplicated and false if it's not
	 */
	private function week_being_duplicated($keyValue1, $keyValue2){
		if(!$this->_db->select_week_pk($keyValue1, $keyValue2)){
			return false;
		}
		return true;
	}

	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $name is the name of the file that the user uploaded
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * this function creates an array with all the lines in the file, checks one line by one line if it matches our pattern and if the data
	 * is not going to be duplicated on the database.
	 * insert the data right into the database if it's not being duplicated
	 * $arrayData contains the amount of data being inserted and the amout of data being duplicated
	 */

		private function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$arrayData = array("duplicate" => 0, "insert" => 0);//contains all data the user tries to duplicate
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];
			if(!preg_match("/^$/", $line))
			{
				if($uploadName=='professor_csv'){
					if(preg_match("/".$pattern."/", $line, $groups))
						if(!$this->teacher_being_duplicated($groups[1])){
							$this->_db->insert_teacher($groups[1], $groups[3], $groups[2], trim($groups[4]));
							$arrayData['insert']++;

						}
							else
								$arrayData["duplicate"]++;
				
				}
				if ($uploadName=='agenda_properties'){
					if(preg_match("/".$pattern."/", $line, $groups))
						
						if(!$this->week_being_duplicated(intval(substr($groups[2], 7)),$groups[1])){
							$date_explode = explode('/', trim($groups[3]));
							$date_obj = date_create(substr($date_explode[2], 0,4) . "-" . $date_explode[1] . "-" . $date_explode[0]);
							$this->_db->insert_week(intval(substr($groups[2], 7)), $groups[2], $date_obj->format('Y-m-d'), $groups[1]);
							$arrayData['insert']++;
						}
							else
								$arrayData["duplicate"]++;
				}
			}
		}
		return $arrayData;

	}

}
