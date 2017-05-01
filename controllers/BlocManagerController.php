<?php
class BlocManagerController{

	private $_db;

	/*
	 * This function decides which function is gonna be called depending on $_POST parameters sent from the view
	 */
	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){
		if(isset($_SESSION['notificationError']))
			$_SESSION['notificationError']='';
		if(isset($_SESSION['notificationSuccess']))
			$_SESSION['notificationSuccess']='';
		$this->process_file('students_csv');
		$this->process_file('lessons_csv');//making sure that the user can upload both files at the same time
		require_once(PATH_VIEW.'blocManager.php');
	}
	/*
	 * $tmp_name is a string containing the absolute path of the temporary location it's being uploaded to on the server
	 * $name is a string containing the name of the file the user uploaded
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * This function checks if the name of the file is conform to what it is supposed to be
	 * and if the data contained are compatible with our database and our constraints.
	 */
	public function is_compatible_file($tmp_name,$name ,$uploadName,$pattern){
		if(!preg_match("/^etudiants\.csv$/",$name)&&!preg_match("/^programme_".$_SESSION['responsibility']."\.csv$/",$name))
			return false;
		$arrayFile = file($tmp_name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];
			if($uploadName=='students_csv'){
				if(!preg_match("/".$pattern."/", $line, $groups))
					return false;

			}
			else if ($uploadName=='lessons_csv'){
				if(!preg_match("/".$pattern."/", $line, $groups))
					return false;
			}
		}
		return true;
	}
	
	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * This function returns a pattern for an upcoming preg_match function depending on $uploadName
	 */
	public function define_pattern($uploadName){
		if($uploadName=='students_csv')
			return "(Bl.*);(.*);(.*);(.*)\n$";
		return "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
	}
	
	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * This function defines a pattern and then moves the file being uploaded in the conf directory if is_compatible_file returned true
	 * and then calls for a data process on the database
	 * also defines a notification to inform the user of what happened
	 */
	public function process_file($uploadName){
		$pattern=$this->define_pattern($uploadName);
		if(isset($_FILES[$uploadName])){
			$tmp_name=$_FILES[$uploadName]['tmp_name'];
			$name=$_FILES[$uploadName]['name'];
			if($this->is_compatible_file($tmp_name,$name,$uploadName,$pattern)){
				move_uploaded_file($tmp_name, PATH_CONF.$name);
				$nbDataDuplicated=sizeof($this->file_to_DB($uploadName,$name,$pattern));
				if($nbDataDuplicated==0)
					$_SESSION['notificationSuccess']="Vos données ont bien été traitées";
				else 
					$_SESSION['notificationSuccess']="Vos données ont bien été traitées mais ".$nbDataDuplicated." données étaient déjà présentes";
			}
			else{
				$_SESSION['notificationError']="Votre fichier n'est pas compatible";
			}
		}
	}
	
	/*
	 * $primaryKey is a string that indicates which pk of which table we are talking about
	 * $keyValue is a string that contains the value of the pk we are looking for
	 * This function return true if the data is being duplicated and false if it's not
	 */
	public function is_being_duplicated($primaryKey, $keyValue){
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
	 * This function creates an array with all the lines in the file, checks one line by one line if it matches our pattern and if the data
	 * is not going to be duplicated on the database.
	 * insert the data right into the database if it's not being duplicated xor in an array containing all data  the user tries to duplicate
	 */
	public function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$arrayDuplicated = array();//contains all data the user tries to duplicate
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
			if($uploadName=='students_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					if(!$this->is_being_duplicated('email_student',$groups[4]))
						$this->_db->insert_student($groups[1], $groups[2], $groups[3], $groups[4]);
					else 
						$arrayDuplicated[$i]=$groups[4];
						
			}
			else if ($uploadName=='lessons_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					if(!$this->is_being_duplicated('lesson_code',$groups[2]))
						$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
					else
						$arrayDuplicated[$i]=$groups[2];
			}
		}
		var_dump($arrayDuplicated);
		return $arrayDuplicated;

	}

}