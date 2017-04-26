<?php
class Db{
	private $_db;
	private static $instance = null;
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Db();
		}
		return self::$instance;
	}
	
	private function __construct() {
		try{
			$this->_db=new PDO('mysql:host=localhost;dbname=ipl_agenda;charset=utf8','root','');
			$this->_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
		}
		catch (PDOException $e) {
			die('Erreur de connexion à la base de données : '.
			$e->getMessage());
		}
	}

	public function insertWeek($week_number, $name, $monday_date, $quadri)
	{
		$req = $this->_db->prepare('INSERT INTO weeks (week_number, name, monday_date, quadri) VALUES (:week_number, :name, :monday_date, :quadri)');
		$req->execute(array('week_number' => $week_number,
							'name' => $name,
							'monday_date' => $monday_date,
							'quadri' => $quadri));
	}

	public function insertTeacher($email_teacher, $name, $last_name, $responsability)
	{
		$req = $this->_db->prepare('INSERT INTO teachers (email_teacher, name, last_name, responsability) VALUES (:email_teacher, :name, :last_name, :responsability)');
		$req->execute(array('email_teacher' => $email_teacher,
							'name' => $name,
							'last_name' => $last_name,
							'responsability' => $responsability));
	}
	public function insertStudent($email_student, $name, $last_name, $number, $bloc)
	{
		/*
		 * Robin@Marco
		 * je suppose que ça doit faire la même chose que
		 * $req->bindValue(':email_teacher',"$email_teacher");
		 * $req->bindValue(':name',"$name");
		 * $req->bindValue(':last_name',"$last_name");
		 * $req->bindValue(':responsability',"$responsability");
		 * $req->bindValue(':anime',"$anime");
		 * $req->execute();
		 */
		$req = $this->_db->prepare('INSERT INTO students (email_student, name, last_name, number, bloc) VALUES (:email_student, :name, :last_name, :number, :bloc)');
		$req->execute(array('email_student' => $email_student,
							'name' => $name,
							'last_name' => $last_name,
							'number' => $number,
							'bloc' => $bloc));
	}
	
	/*
	 * Robin@Marco
	 * il faudrait faire la différence entre UE et AA dans la db, une UE peut avoir plusieurs AA.
	 * ex : 1)Introduction aux systèmes d'exploitation est une UE et possède 2 AA -> Linux et OS qui ne sont pas des seances types
	 *      2)Anglais est une UE qui n'a pas d'AA
	 * le champs type de la table lessons ne permet pas :
	 * - de savoir pour une AA à quelle UE elle appartient dans l'état actuel
	 * - de savoir si une UE possède des AA ou pas 
	 * -> il faudrait une relation 1 à 0:1:n entre UE et AA respectivement
	 */
	public function insertLesson($name, $type, $quadri, $credits, $abbreviation)
	{
		$req = $this->_db->prepare('INSERT INTO lessons (name, type, quadri, credits, abbreviation) VALUES (:name, :type, :quadri, :credits, :abbreviation)');
		$req->execute(array('name' => $name,
							'type' => $type,
							'quadri' => $quadri,
							'credits' => $credits,
							'abbreviation' => $abbreviation));
	}
	public function insertSerie($number,$bloc)
	{
		/*here the primary key cannot be automaticaly incremented since the primary key is number and bloc binded together 
		 *ie:serie1,bloc1 / serie1,bloc2
		 */
		$req = $this->_db->prepare('INSERT INTO series (number,bloc) VALUES (:number, :bloc)');
		$req->execute(array('number' => $number,
							'bloc' => $bloc));
	}
}
?>