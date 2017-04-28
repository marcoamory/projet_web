<?php
class BlocManagerController{

	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){
		if(isset($_SESSION['notificationError']))
			$_SESSION['notificationError']='';
		if(isset($_SESSION['notificationSuccess']))
			$_SESSION['notificationSuccess']='';
		$this->process_file('students_csv');
		$this->process_file('lessons_csv');
		require_once(PATH_VIEW.'blocManager.php');
	}
	
	public function is_compatible_file($tmp_name,$uploadName,$pattern){
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
				if(isset($_POST['blocNumber'])){
					if(!preg_match("/^I".$_POST['blocNumber']."/", $groups[2]))
						return false;
				}
			}
		}
		return true;
	}
	
	public function define_pattern($uploadName){
		if($uploadName=='students_csv')
			return "(.*);(.*);(.*);(.*)\n$";
		return "(.*);(.*);(.*);(.*);(.*);(.*)\n$";
	}
	
	// ATTENTION DANS L'ETAT ACTUEL DES CHOSES VIA L'UPLOAD D'ETUDIANTS UPLOAD LES PROGRAMMES VA UPLOAD LES PROGRAMMES DANS LA TABLE ETUDIANTS !!!!
	public function process_file($uploadName){
		$pattern=$this->define_pattern($uploadName);
		if(isset($_FILES[$uploadName])){
			$tmp_name=$_FILES[$uploadName]['tmp_name'];
			$name=$_FILES[$uploadName]['name'];
			if($this->is_compatible_file($tmp_name,$uploadName,$pattern)){
				move_uploaded_file($tmp_name, PATH_CONF.$name);
				$this->file_to_DB($uploadName,$name,$pattern);
				$_SESSION['notificationSuccess']="Vos données ont bien été traitées";
			}
			else{
				$_SESSION['notificationError']="Votre fichier n'est pas compatible:
						-Êtes vous sûrs de ne pas dubliquer les données?
						-Votre fichier est-il conforme au format demandé ?";
			}
		}
	}
	
	/* créer une fonction qui vérifie si ils ne sont pas déjà dans la base de donnée
	 * méditer sur : le faire via try/catch ou alors un for qui fait un select sur la donnée courrante du fichier pour vérifier
	 * si elle n'existe pas déjà, il suffit de vérifier la pk pas le reste.
	public function is_being_duplicated{//il pourrait renvoyer un tableau des éléments qui sont dupliqués pour renvoyer à la vue telle telle et telle données sont déjà présentes
	
	}
	 */
		
	// si c'est un RBs il faut qu'il puisse ajouter le bloc dans lequel il veut l'ajouter et vérifier que cela correspond bien au 2 premieres lettres 
	// de lesson_code du cours en question.
	public function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
			if($uploadName=='students_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					$this->_db->insert_student($groups[1], $groups[2], $groups[3], $groups[4]);
			}
			else if ($uploadName=='lessons_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
			}
		}

	}

}