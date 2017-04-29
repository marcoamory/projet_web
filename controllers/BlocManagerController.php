<?php
class BlocManagerController{

	private $_db;

	public function __construct($db){
		$this->_db = $db;
	}

	public function run(){
		var_dump(!$this->_db->search_lesson("I1020"));
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
			return "(Bl.*);(.*);(.*);(.*)\n$";
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
				$_SESSION['notificationSuccess']="Vos donn�es ont bien �t� trait�es";
			}
			else{
				$_SESSION['notificationError']="Votre fichier n'est pas compatible:
						-�tes vous s�rs de ne pas dubliquer les donn�es?
						-Votre fichier est-il conforme au format demand� ?";
			}
		}
	}
	
	/* cr�er une fonction qui v�rifie si ils ne sont pas d�j� dans la base de donn�e
	 * m�diter sur : le faire via try/catch ou alors un for qui fait un select sur la donn�e courrante du fichier pour v�rifier
	 * si elle n'existe pas d�j�, il suffit de v�rifier la pk pas le reste.*/
	public function is_being_duplicated($primaryKey, $keyValue){//il pourrait renvoyer un tableau des �l�ments qui sont dupliqu�s pour renvoyer � la vue telle telle et telle donn�es sont d�j� pr�sentes
		if($primaryKey=='email_student'){
			if(!$this->_db->search_student($keyValue));
				return true;
		}
		elseif($primaryKey=='lesson_code'){
			if(!$this->_db->search_lesson($keyValue));
				return true;
		}
		else
			return false;
	}
	 
		
	// si c'est un RBs il faut qu'il puisse ajouter le bloc dans lequel il veut l'ajouter et v�rifier que cela correspond bien au 2 premieres lettres 
	// de lesson_code du cours en question.
	public function file_to_DB($uploadName,$name,$pattern){
		$arrayFile = file(PATH_CONF . $name);
		$nb_lines = count($arrayFile);
		for($i = 1; $i < $nb_lines; $i++){
			$line = $arrayFile[$i];	
			if($uploadName=='students_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					//if(!$this->is_being_duplicated('email_student',$groups[4]))
						$this->_db->insert_student($groups[1], $groups[2], $groups[3], $groups[4]);
			}
			else if ($uploadName=='lessons_csv'){
				if(preg_match("/".$pattern."/", $line, $groups))
					//if(!$this->is_being_duplicated('lesson_code',$groups[2]))
						$this->_db->insert_lesson($groups[1], $groups[2], $groups[3], $groups[4], $groups[5], $groups[6]);
			}
		}

	}

}