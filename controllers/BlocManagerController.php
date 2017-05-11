<?php
/*
 * créer séries en first, après ST
 */
class BlocManagerController{

	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}
	
	/*
	 * This function decides which function is gonna be called depending on $_POST parameters sent from the view
	 */
	public function run(){
		var_dump($_POST);
		$serie_array=$this->_db->select_serie_star_bloc($_SESSION['responsibility']);
		$lesson_array=$this->_db->select_lesson_name_and_lesson_code("I".substr($_SESSION['responsibility'],4,5));
		if(isset($_SESSION['notification_error']))
			unset($_SESSION['notification_error']);
		if(isset($_SESSION['notification_success']))
			unset($_SESSION['notification_success']);
		if(isset($_SESSION['notification_warning']))
			unset($_SESSION['notification_warning']);
		if(isset($_POST['new_serie']))
			$this->modify_serie();
		if((isset($_POST['modify_serie']))){
			if((empty($_POST['modify_serie']))){
				$_SESSION['notification_error']="Entrez une série à modifier.";
				require_once(PATH_VIEW.'blocManager.php');
			}
			else{
				$serie=$this->_db->select_serie_pk($_POST['modify_serie'],$_SESSION['responsibility']);
				$students_array=$this->_db->select_student_serie($serie->get_number(),$_SESSION['responsibility']);
				require_once(PATH_VIEW.'modifySerie.php');
			}
		}
		elseif(isset($_POST['nb_series'])){
			if(empty($_POST['nb_series']))
				$_SESSION['notification_error']="Entrez combien de séries vous désirez";
			else 
				$this->create_series();
			$serie_array=$this->_db->select_serie_star_bloc($_SESSION['responsibility']);
			require_once(PATH_VIEW.'blocManager.php');
		}
		elseif(isset($_POST['delete_serie'])){
			if(empty($_POST['delete_serie']))
				$_SESSION['notification_error']="Entrez une série à supprimer.";
			else
				$this->delete_serie();
			$serie_array=$this->_db->select_serie_star_bloc($_SESSION['responsibility']);
			require_once(PATH_VIEW.'blocManager.php');
		}
		elseif(isset($_FILES['lessons_csv'])){
			if($_FILES['lessons_csv']['size']==0)
				$_SESSION['notification_error']="Choisissez un fichier à uploader";
			else
				$this->process_file('lessons_csv');
			require_once(PATH_VIEW.'blocManager.php');
		}
		elseif(isset($_POST['lesson_fk'])&&isset($_POST['series_chosen'])){
			if(empty($_POST['series_chosen']))
				$_SESSION['notification_error']="Entrez une UE/AA et la série pour laquelle vous voulez créer la séance type";
			else
				$this->create_session();
			require_once(PATH_VIEW.'blocManager.php');
		}
		else 
			require_once(PATH_VIEW.'blocManager.php');
	}
	
	private function modify_serie(){
		$nb_students_modified=0;
		for($i = 0; $i < count($_POST['new_serie']) ; $i++){
			if($_POST['new_serie'][$i]!="")
				$this->_db->update_serie_student($_POST['new_serie'][$i],$_POST['students_modified'][$i]);
		}
	}
	
	private function delete_serie(){
		if(!$this->_db->select_serie_pk($_POST['delete_serie'],$_SESSION['responsibility']))
			$_SESSION['notification_error']="Cette série n'existe pas";
		else {
			$_SESSION['notification_success']="la série a bien été supprimée, ses éventuels élèves sont désormais orphelins";
			$this->_db->update_student_serie_null($_POST['delete_serie']);
			$this->_db->drop_serie_pk($_POST['delete_serie'],$_SESSION['responsibility']);
		}
	}
	
	private function create_series(){
		$nb_series=intval($_POST['nb_series']);
		$students_array=$this->_db->select_student_bloc($_SESSION['responsibility']);
		$nb_students=sizeof($students_array);
		$nb_student_serie=floor($nb_students/$nb_series);
		if(empty($students_array)||$nb_students<$nb_series)
			$_SESSION['notification_error']="Pas ou pas assez d'étudiants dans la base de donnée";
		else {
			if($this->_db->count_serie_by_serie_bloc($_SESSION['responsibility'])>0)
				$_SESSION['notification_warning']="Les séries du ". $_SESSION['responsibility']. " ont déjà été introduites";
			else{
				$current_student=0;
				for($i = 1; $i <= $nb_series ; $i++){
					$this->_db->insert_serie($i,$_SESSION['responsibility']);
					for($j = 0; $j < $nb_student_serie ; $j++){
						$this->_db->update_serie_student($i,$students_array[$current_student]->getEmail());
						$current_student++;
					}
					if($i==$nb_series){//ici ça marche pas
						for($j = 1; $j <= $nb_students%$nb_series; $j++){
							$this->_db->update_serie_student($j,$students_array[$current_student]->getEmail());
							$current_student++;
						}
					}
				}
			}
		}
	}
	
	private function create_session(){
		echo"here";
		if(isset($_POST['series_chosen'])){
			$this->_db->insert_session(htmlspecialchars($_POST['name']),$_POST['lesson_fk'],$_POST['presence_type']);
			for($i=0;$i<count($_POST['series_chosen']);$i++){
				$this->_db->insert_session_serie($this->_db->select_session_pk()->id_session,$_POST['series_chosen'][$i],$_SESSION['responsibility']);
			}
			$_SESSION['notification_success']="Votre séance type a bien été créée";
		}
		
	}
	/*
	 * $tmp_name is a string containing the absolute path of the temporary location it's being uploaded to on the server
	 * $name is a string containing the name of the file the user uploaded
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * $pattern is a string containing the pattern necessary to the preg_match function depending on $uploadName
	 * This function checks if the name of the file is conform to what it is supposed to be
	 * and if the data contained are compatible with our database and our constraints.
	 */
	private function is_compatible_file($tmp_name,$name ,$uploadName,$pattern){
		if(!preg_match("/^programme_".$_SESSION['responsibility']."\.csv$/",$name))
			return false;
		$arrayFile = file($tmp_name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];
			if ($uploadName=='lessons_csv'){
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
	private function define_pattern($uploadName){
		return "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
	}
	
	/*
	 * $uploadName is a string telling the function wether we are uploading a file of student or a file of lessons
	 * This function defines a pattern and then moves the file being uploaded in the conf directory if is_compatible_file returned true
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
	 * This function return true if the data is being duplicated and false if it's not
	 */
	private function is_being_duplicated($primaryKey, $keyValue){
		if($primaryKey=='lesson_code'){
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
	private function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$arrayDuplicated = array();//contains all data the user tries to duplicate
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
			if ($uploadName=='lessons_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					if(!$this->is_being_duplicated('lesson_code',$groups[2])){
						$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
					}
					else
						$arrayDuplicated[$i]=$groups[2];
			}
		}
		return $arrayDuplicated;
	}

}