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
									DELETE FROM teachers WHERE responsibility != "true";
									DELETE FROM weeks;
									DELETE FROM lessons;');
		$req->execute();
	}
	
	public function drop_serie_pk($serie_number,$serie_bloc)
	{
		$req = $this->_db->prepare('DELETE FROM series where serie_number=:serie_number and lower(serie_bloc)=:serie_bloc');
		$req->execute(array('serie_bloc' => $serie_bloc,
							'serie_number' => $serie_number));
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
		$req->execute(array('name' => utf8_encode($name),
							'lesson_code' => utf8_encode($lesson_code),
							'quadri' => utf8_encode($quadri),
							'type' => utf8_encode($type),
							'credits' => utf8_encode($credits),
							'abbreviation' => utf8_encode($abbreviation)));

	}
	
	public function insert_serie($serie_number,$bloc)
	{
		/*here the primary key cannot be automaticaly incremented since the primary key is number and bloc binded together 
		 *ie:serie1,bloc1 / serie1,bloc2
		 */
		$req = $this->_db->prepare('INSERT INTO series (serie_number,serie_bloc) VALUES (:serie_number, :serie_bloc)');
		$req->execute(array('serie_number' => $serie_number,
							'serie_bloc' => $bloc));
	}
	
	public function count_serie_by_serie_bloc($serie_bloc){
		$req = $this->_db->prepare("SELECT count(*) as total from series where serie_bloc =:serie_bloc");
		$req->execute(array('serie_bloc' => $serie_bloc));
		$result=$req->fetch();
		$req->closeCursor();
		return intval(substr($result->total,0,strlen($result->total)));
	}
	
	public function count_serie_by_serie_number($serie_number){
		$req = $this->_db->prepare("SELECT count(*) as total from series where serie_number =:serie_number");
		$req->execute(array('serie_number' => $serie_number));
		$result=$req->fetch();
		$req->closeCursor();
		return intval(substr($result->total,0,strlen($result->total)));
	}
	
	public function update_student_serie_null($serie_number){
		$req = $this->_db->prepare("UPDATE students SET serie_number=null WHERE serie_number=:serie_number");
		$req->execute(array('serie_number' => $serie_number));
	}
	
	public function update_serie_student($serie_number,$email_student){
		$req = $this->_db->prepare("UPDATE students SET serie_number=:serie_number WHERE email_student=:email_student");
		$req->execute(array('serie_number' => $serie_number,
							'email_student' => $email_student));
	}

	public function insert_presence_sheet($email_teacher, $id_session, $week_number){
		$req = $this->_db->prepare("INSERT INTO presence_sheets (email_teacher, id_session, week_number) VALUES (:email_teacher, :id_session, :week_number)");
		$req->execute(array("email_teacher" => $email_teacher,
							"id_session" => $id_session,
							"week_number" => $week_number));
	}
	
	/*
	 * careful, the student's email must exist before using this query
	 */
	public function insert_presence($id_sheet, $email_student,$state,$grade)
	{
		$req = $this->_db->prepare('INSERT INTO presences (id_sheet, email_student, state, grade) VALUES (:id_sheet, :email_student, :state, :grade)');
		$req->execute(array('id_sheet' => $id_sheet,
		 					'email_student' => $email_student,
							'state' => $state,
							'grade' => $grade));
	}

	public function select_student_pk($email)
	{
		$req = $this->_db->prepare("SELECT * FROM students WHERE email_student = :email_student");
		$req->execute(array("email_student" => $email));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}
	
	public function select_student_bloc($bloc){
		$req = $this->_db->prepare("SELECT * FROM students where lower(bloc)=:bloc");
		$req->execute(array('bloc' => $bloc));
		$students_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$students_array[] = new Student($row->email_student, $row->first_name, $row->last_name, $row->bloc, $row->serie_number);
			}
		}
		$req->closeCursor();
		return $students_array;
	}
	
	public function select_student_star(){
		$req = $this->_db->prepare("SELECT * FROM students");
		$req->execute();
		$students_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$students_array[] = new Student($row->email_student, $row->first_name, $row->last_name, $row->bloc, $row->serie_number);
			}
		}
		$req->closeCursor();
		return $students_array;
	}

	public function select_serie_bloc($bloc)
	{
		$req = $this->_db->prepare("SELECT serie_number FROM series WHERE serie_bloc = :serie_bloc");
		$req->execute(array("serie_bloc" => $bloc));
		$serie_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$serie_array[] = $row->serie_number;
			}
		}
		$req->closeCursor();
		return $serie_array;
	}



	public function select_lesson_bloc($bloc, $quadri){
		$req = $this->_db->prepare("SELECT name FROM lessons WHERE SUBSTRING(lesson_code,2,1) = :bloc AND quadri = :quadri");
		$req->execute(array("bloc" => $bloc, "quadri" => strtoupper($quadri)));
		$lesson_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$lesson_array[] = $row->name;
			}
		}
		$req->closeCursor();
		
		return $lesson_array;
	}
	
	public function insert_session($name,$lesson_code){
		$req = $this->_db->prepare('INSERT INTO sessions (name, lesson_code) VALUES (:name, :lesson_code)');
		$req->execute(array('name' => $name,
							'lesson_code' => $lesson_code));
	}
	
	
	public function insert_session_serie($session_code,$serie_number,$bloc_number){
		$req = $this->_db->prepare('INSERT INTO sessions_series (number, bloc, id_session) VALUES (:number, :bloc, :id_session)');
		$req->execute(array('number' => $serie_number,
							'bloc' => $bloc_number,
							'id_session' => $session_code));
	}
	
	public function select_session_pk(){
		$req = $this->_db->prepare("SELECT id_session FROM sessions ORDER BY id_session DESC");
		$req->execute(array());
		$result = $req->fetch();
		$req->closeCursor();
		var_dump($result);
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
	
	public function select_serie_star_bloc($serie_bloc){
		$req = $this->_db->prepare("SELECT * FROM series where serie_bloc=:serie_bloc");
		$req->execute(array("serie_bloc" => $serie_bloc));
		$series_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$series_array[] = new Serie($row->serie_number, $row->serie_bloc);
			}
		}
		$req->closeCursor();
		return $series_array;
	}
	
	public function select_star_week(){
		$req = $this->_db->prepare("SELECT * FROM weeks");
		$req->execute();
		$result = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$result[] = $row;
			}
		}
		$req->closeCursor();
		return $result;
	}
	
	public function select_lesson_name_and_lesson_code($lesson_code){
		$req = $this->_db->prepare("SELECT name, lesson_code FROM lessons where lesson_code like :lesson_code");
		$req->execute(array("lesson_code" => $lesson_code."%"));
		$result = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$result[] = $row;
			}
		}
		$req->closeCursor();
		return $result;
	}
	
	public function select_serie_pk($serie_number,$serie_bloc)
	{
		$req = $this->_db->prepare("SELECT * FROM series WHERE serie_number = :serie_number AND lower(serie_bloc) = :serie_bloc  ");
		$req->execute(array("serie_number" => $serie_number,
							"serie_bloc" => $serie_bloc));
		$result = $req->fetch();
		$req->closeCursor();
		$result = new Serie($result->serie_number,$result->serie_bloc);
		return $result;
	}
	

	public function select_student_serie($serie_number, $bloc)
	{
		$req = $this->_db->prepare("SELECT * FROM students WHERE serie_number = :serie_number AND bloc = :bloc ORDER BY last_name");
		$req->execute(array("serie_number" => $serie_number,
							"bloc" => $bloc));
		$students_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$students_array[] = new Student($row->email_student, $row->first_name, $row->last_name, $row->bloc, $row->serie_number);
			}
		}
		$req->closeCursor();
		return $students_array;
	}
	

	public function select_teacher_pk($email)
	{
		$req = $this->_db->prepare("SELECT email_teacher, first_name, last_name, responsibility FROM teachers WHERE email_teacher = :email_teacher");
		$req->execute(array("email_teacher" => $email));
		$result = $req->fetch();
		$req->closeCursor();
		if(!empty($result)){
			$result = new Teacher($result->email_teacher,$result->first_name, $result->last_name, $result->responsibility);
		}
		return $result;
	}

	public function select_week_pk($week_number)
	{
		$req = $this->_db->prepare("SELECT * FROM weeks WHERE week_number = :week_number");
		$req->execute(array("week_number" => $week_number));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}

	public function select_current_week(){
		$req = $this->_db->query("SELECT * FROM weeks WHERE monday_date <= CURDATE() AND CURDATE() < DATE_ADD(monday_date, INTERVAL 7 DAY)");
		$result = $req->fetch();
		return $result;
	}

	public function select_session_serie($bloc, $serie, $quadri){
		$req = $this->_db->prepare("SELECT s.name name, ss.time_slot time_slot, s.id_session id_session
									FROM sessions s, sessions_series ss, lessons l
									WHERE s.id_session = ss.id_session AND l.lesson_code = s.lesson_code
									AND (l.quadri = :quadri OR l.quadri = 'Q12') AND ss.number = :serie AND ss.bloc = :bloc");
		$req->execute(array("quadri" => strtoupper($quadri), "serie" => $serie, "bloc" => $bloc));
		$session_array = array();
		if ($req->rowcount()!=0) {
			while ($row = $req->fetch()) {
				$session_array[] = $row;
			}
		}
		$req->closeCursor();
		
		return $session_array; 
	}

	public function select_presence_sheet($email_teacher, $id_session, $week_number){
		$req = $this->_db->prepare("SELECT * FROM presence_sheets WHERE email_teacher = :email_teacher AND id_session = :id_session AND week_number = :week_number");
		$req->execute(array("email_teacher" => $email_teacher,
							"id_session" => $id_session,
							"week_number" => $week_number));
		$result = $req->fetch();
		$req->closeCursor();
		return $result->id_sheet;
	}

}
?>