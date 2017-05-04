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
		if(isset($_SESSION['notification_error']))
			unset($_SESSION['notification_error']);
		if(isset($_SESSION['notification_success']))
			unset($_SESSION['notification_success']);
		if(isset($_SESSION['notification_warning']))
			unset($_SESSION['notification_warning']);
		if(isset($_POST['nb_series'])){
			if(empty($_POST['nb_series']))
				$_SESSION['notification_error']="Entrez combien de séries vous désirez";
			else 
				$this->create_series();
		}
		if(isset($_POST['delete_serie'])&&isset($_POST['bloc_serie_delete'])){
			if(empty($_POST['delete_serie'])||empty($_POST['bloc_serie_delete']))
				$_SESSION['notification_error']="Entrez une série à supprimer et son bloc correspondant";
			else
				$this->delete_series();
		}
		if(isset($_FILES['lessons_csv'])){
			if($_FILES['lessons_csv']['size']==0)
				$_SESSION['notification_error']="Choisissez un fichier à uploader";
			else
				$this->process_file('lessons_csv');
		}
		if(isset($_POST['name'])&&isset($_POST['lesson_fk'])&&isset($_POST['serie_fk'])){
			if(empty($_POST['lesson_fk'])||empty($_POST['serie_fk']))
				$_SESSION['notification_error']="Entrez une UE/AA et la série pour laquelle vous voulez créer la séance type";
			else
				$this->create_session();
		}
		elseif(isset($_POST['modify_serie'])&&isset($_POST['bloc_serie_modify'])){
			if(empty($_POST['modify_serie'])||empty($_POST['bloc_serie_modify']))
				$_SESSION['notification_error']="Entrez une série à modifier et son bloc correspondant";
			else
				$this->modify_serie();
		}
		else {
			unset($_SESSION['modify_serie']);
			require_once(PATH_VIEW.'blocManager.php');
		}
	}
	
	public function modify_serie(){
		/*
		 * afficher un tableau des étudiants de la série
		 */
		$serie=$this->_db->select_serie_pk($_POST['modify_serie'],$_POST['bloc_serie_modify']);
		$students_array=$this->_db->select_student_serie($serie->get_number());
		if(empty($serie)){
			$_SESSION['notification_error']='Cette série n\'existe pas';
		}
		elseif(isset($_SESSION['modify_serie'])){
			$nb_serie_modify=count($_POST['new_serie']);
			for ($i=0;$i<$nb_serie_modify;$i++){
				;
			}
		}
		else {
			$_SESSION['modify_serie']=true;
			require_once(PATH_VIEW.'blocManager.php');
		}
	}
	
	public function delete_serie(){
		
	}
	
	public function create_series(){
		$nb_series=intval($_POST['nb_series']);
		$nb_students=sizeof($students_array);
		$students_array=$this->_db->select_student_star();
		if(empty($students_array)||$nb_students<$nb_series)
			$_SESSION['notification_error']="Pas ou pas assez d'étudiants dans la base de donnée";
		/*
		 * il faudrait permettre de choisir une série dans la vue et d'afficher les étudiants présents dans cette série et pouvoir modifier
		 * facilement ces données
		 */
		else {
			for($i = 0; $i < $nb_students ; $i++){
				for($i = 0; $i < $nb_students/$nb_series ; $i++){
	
					/*
					 * répartir les étudiants dans les séries
					 */;
				}
			}
		}
	}
	
	public function create_session(){
		
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
	public function define_pattern($uploadName){
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
	public function is_being_duplicated($primaryKey, $keyValue){
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
	public function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$arrayDuplicated = array();//contains all data the user tries to duplicate
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
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