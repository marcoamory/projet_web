<?php
/*
 * Apparement le nom du fichier "devrait" être obligatoirement nommé programme_blocX pour pouvoir être uploadé ou du moins avoir ce nom l� sur le serveur
 * à demander.
 */
class BlocsManagerController{

	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}
	
	/*
	 * this function decides which function is gonna be called depending on $_POST parameters sent from the view
	 */
	public function run(){
		if(isset($_SESSION['notification_error']))
			unset($_SESSION['notification_error']);
		if(isset($_SESSION['notification_success']))
			unset($_SESSION['notification_success']);
		if(isset($_SESSION['notification_warning']))
			unset($_SESSION['notification_warning']);
		if(isset($_FILES['students_csv']))
			$this->process_file('students_csv');
		elseif(isset($_FILES['lessons_csv'])&&isset($_POST['blocNumber']))
			$this->process_file('lessons_csv');
		elseif(isset($_FILES['lessons_csv'])&&!isset($_POST['blocNumber']))
			$_SESSION['notification_error']="Veuillez mentionner un bloc";
		elseif(!isset($_FILES['lessons_csv'])&&isset($_POST['wipe_data'])){
			$this->wipe_data();
			$_SESSION['notification_success'] = "Les données annuelles ont bien été effacées";
		}
		require_once(PATH_VIEW.'blocsManager.php');	
	}
	/*
	 * necessary to the admin aswell
	 * wipes out all the data in the DB
	 */
	private function wipe_data(){
		$this->_db->drop_all_data();
		header("Location: index.php?action=logout");
		die();
	}
	/*
	 * $tmp_name is a string containing the absolute path of the temporary location it's being uploaded to on the server
	 * $name is a string containing the name of the file the user uploaded
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * this function checks if the file is a .csv, compatible with our database and our constraints
	 */
	private function is_compatible_file($tmp_name,$name ,$uploadName,$pattern){
		if(!preg_match("/^etudiants\.csv$/",$name)&&!preg_match("/^programme_bloc[1-3]\.csv$/",$name))
			return false;
		$arrayFile = file($tmp_name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];
			if ($uploadName=='lessons_csv'){
				if(!preg_match("/".$pattern."/", $line, $groups))
					return false;
				if(isset($_POST['blocNumber'])){//seulement pour le responsable blocS
					if(!preg_match("/^I".$_POST['blocNumber']."/", $groups[2]))
						return false;
				}
			}
		}
		return true;
	}

	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * this function returns a pattern for an upcoming preg_match function depending on $uploadName
	 */
	private function define_pattern($uploadName){
		if($uploadName=='students_csv')
			return "(Bl.*);(.*);(.*);(.*)\n$";
		return "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
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
				$nbDataDuplicated=sizeof($this->file_to_DB($uploadName,$name,$pattern));
				if($nbDataDuplicated==0)
					$_SESSION['notification_success']="Vos données ont bien été traitées";
					else
						$_SESSION['notification_warning']="Vos données ont bien été traitées mais ".$nbDataDuplicated." données étaient déjà présentes";
			}
			else{
				$_SESSION['notification_error']="Votre fichier n'est pas compatible";
			}
		}
	}

	/*
	 * $primaryKey is a string that indicates which pk of which table we are talking about
	 * $keyValue is a string that contains the value of the pk we are looking for
	 * this function return true if the data is being duplicated and false if it's not
	 */
	private function is_being_duplicated($primaryKey, $keyValue){
		if($primaryKey=='email_student'){
			if(!$this->_db->select_student_pk($keyValue)){
				return false;
			}
		}
		elseif($primaryKey=='lesson_code'){
			if(!$this->_db->select_lesson_pk($keyValue)){
				return false;
			}
		}
		return true;
	}

	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $name is the name of the file that the user uploaded
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * this function creates an array with all the lines in the file, checks one line by one line if it matches our pattern and if the data
	 * is not going to be duplicated on the database.
	 * insert the data right into the database if it's not being duplicated xor in an array containing all data  the user tries to duplicate
	 */
	private function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$arrayDuplicated = array();//contains all data the user tries to duplicate
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];
			if($uploadName=='students_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					if(!$this->is_being_duplicated('email_student',trim($groups[4])))
						$this->_db->insert_student($groups[1], $groups[2], $groups[3], trim($groups[4]));
						else
							$arrayDuplicated[$i]=$groups[4];
			
			}
			if ($uploadName=='lessons_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					if(!$this->is_being_duplicated('lesson_code',$groups[2]))
						$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
						else
							$arrayDuplicated[$i]=$groups[2];
			}
		}
		return $arrayDuplicated;

	}

}