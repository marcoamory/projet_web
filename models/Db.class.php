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
	
	/*
	 * this particuliar order is necessary because of foreign keys
	 * students depends on series
	 */
	public function drop_all_data()
	{
	
		$req = $this->_db->prepare('DELETE FROM presence_sheets;
									DELETE FROM presences;
									DELETE FROM sessions_series;
									DELETE FROM sessions;
									DELETE FROM series;
									DELETE FROM students;
									DELETE FROM teachers;
									DELETE FROM weeks;
									DELETE FROM lessons;');
		$req->execute();
	}
	
	public function insert_week($week_number, $name, $monday_date, $quadri)
	{
		$req = $this->_db->prepare('INSERT INTO weeks (week_number, name, monday_date, quadri) VALUES (:week_number, :name, :monday_date, :quadri)');
		$req->execute(array('week_number' => $week_number,
							'name' => $name,
							'monday_date' => $monday_date,
							'quadri' => $quadri));
	}

	public function insert_teacher($email_teacher, $first_name, $last_name, $responsibility)
	{
		$req = $this->_db->prepare('INSERT INTO teachers (email_teacher, first_name, last_name, responsibility) VALUES (:email_teacher, :first_name, :last_name, :responsibility)');
		$req->execute(array('email_teacher' => $email_teacher,
							'first_name' => $first_name,
							'last_name' => $last_name,
							'responsibility' => $responsibility));
	}
	/*
	 * pas de number dans la db, pas dans le csv en tout cas
	 * first_name plutot que lastname
	 */
	public function insert_student($bloc, $last_name, $first_name, $email_student)
	{

		$req = $this->_db->prepare('INSERT INTO students (bloc, last_name, first_name, email_student) VALUES (:bloc, :last_name, :first_name, :email_student)');
		$req->execute(array('email_student' => $email_student,
							'last_name' => $last_name,
							'first_name' => $first_name,
							'bloc' => $bloc));
	}
	
	public function insert_lesson($name, $lesson_code, $quadri, $type, $credits, $abbreviation)
	{
		$req = $this->_db->prepare('INSERT INTO lessons (name, lesson_code, quadri, type, credits, abbreviation) VALUES (:name, :lesson_code, :quadri, :type, :credits, :abbreviation)');
		$req->execute(array('name' => $name,
							'lesson_code' => $lesson_code,
							'quadri' => $quadri,
							'type' => $type,
							'credits' => $credits,
							'abbreviation' => $abbreviation));
	}
	
	public function insert_serie($number_serie,$bloc)
	{
		/*here the primary key cannot be automaticaly incremented since the primary key is number and bloc binded together 
		 *ie:serie1,bloc1 / serie1,bloc2
		 */
		$req = $this->_db->prepare('INSERT INTO series (number_serie,bloc) VALUES (:number_serie, :bloc)');
		$req->execute(array('number_serie' => $number_serie,
							'bloc' => $bloc));
	}
	
	/*
	 * careful, the student's email must exist before using this query
	 */
	public function insert_presence($email_student,$state,$grade)
	{
		$req = $this->_db->prepare('INSERT INTO presences (email_student, state, grade) VALUES (:email_student, :state, :grade)');
		$req->execute(array('email_student' => $email_student,
							'state' => $state,
							'grade' => $grade));
	}

	public function select_student_pk($email)
	{
		$req = $this->_db->prepare("SELECT * FROM students WHERE email_student =:email_student");
		$req->execute(array("email_student" => $email));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}
	
	public function select_star(){
		$req = $this->_db->prepare("SELECT * FROM students");
		$req->execute();
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}
	
	public function select_lesson_pk($lesson_code)
	{
		$req = $this->_db->prepare("SELECT * FROM lessons WHERE lesson_code = :lesson_code");
		$req->execute(array("lesson_code" => $lesson_code));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}

	public function select_teacher_pk($email)
	{
		$req = $this->_db->prepare("SELECT email_teacher, first_name, last_name, responsibility FROM teachers WHERE email_teacher = :email_teacher");
		$req->execute(array("email_teacher" => $email));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}
}
?>