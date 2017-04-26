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
}
?>