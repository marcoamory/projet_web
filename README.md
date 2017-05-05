# projet_web
projet web ipl 2017

<?php
/*
 * cr�er s�ries en first, apr�s ST
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
				$_SESSION['notification_error']="Entrez combien de s�ries vous d�sirez";
			else 
				$this->create_series();
		}
		if(isset($_POST['delete_serie'])&&isset($_POST['bloc_serie_delete'])){
			if(empty($_POST['delete_serie'])||empty($_POST['bloc_serie_delete']))
				$_SESSION['notification_error']="Entrez une s�rie � supprimer et son bloc correspondant";
			else
				$this->delete_serie();
		}
		if(isset($_FILES['lessons_csv'])){
			if($_FILES['lessons_csv']['size']==0)
				$_SESSION['notification_error']="Choisissez un fichier � uploader";
			else
				$this->process_file('lessons_csv');
		}
		if(isset($_POST['name'])&&isset($_POST['lesson_fk'])&&isset($_POST['serie_fk'])){
			if(empty($_POST['lesson_fk'])||empty($_POST['serie_fk']))
				$_SESSION['notification_error']="Entrez une UE/AA et la s�rie pour laquelle vous voulez cr�er la s�ance type";
			else
				$this->create_session();
		}
		elseif(isset($_POST['modify_serie'])&&isset($_POST['bloc_serie_modify'])){
			if(empty($_POST['modify_serie'])||empty($_POST['bloc_serie_modify']))
				$_SESSION['notification_error']="Entrez une s�rie � modifier et son bloc correspondant";
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
		 * afficher un tableau des �tudiants de la s�rie
		 */
		$serie=$this->_db->select_serie_pk($_POST['modify_serie'],$_POST['bloc_serie_modify']);
		$students_array=$this->_db->select_student_serie($serie->get_number());
		if(empty($serie)){
			$_SESSION['notification_error']='Cette s�rie n\'existe pas';
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
		if(!$this->_db->select_serie_pk($_POST['delete_serie'],$_POST['bloc_serie_delete']))
			$_SESSION['notification_error']="Cette s�rie n'existe pas";
		else {
			$_SESSION['notification_success']="la s�rie a bien �t� supprim�e, ses �ventuels �l�ves sont d�sormais orphelins";
			$this->_db->update_student_serie_null($_POST['delete_serie']);
			$this->_db->drop_serie_pk($_POST['delete_serie'],$_POST['bloc_serie_delete']);
		}
	}
	
	public function create_series(){
		$nb_series=intval($_POST['nb_series']);
		$students_array=$this->_db->select_student_star();
		$nb_students=sizeof($students_array);
		if(empty($students_array)||$nb_students<$nb_series)
			$_SESSION['notification_error']="Pas ou pas assez d'�tudiants dans la base de donn�e";
		else {
			if($this->_db->count_serie(intval(substr($_SESSION['responsibility'],4,1)))>0)
				$_SESSION['notification_warning']="Les s�ries du ". $_SESSION['responsibility']. " ont d�j� �t� introduites";
			else{
				$current_student=0;
				for($i = 1; $i <= $nb_series ; $i++){
					$this->_db->insert_serie($i,intval(substr($_SESSION['responsibility'],4,1)));
					for($j = 0; $j < floor($nb_students/$nb_series) ; $j++){
						$this->_db->update_serie_student($students_array[$current_student]->getEmail(),$i);
						$current_student++;
					}
					if($i==$nb_series){
						for($i = 0; $i < $nb_students-($nb_series*floor($nb_students/$nb_series)) ; $i++){
							$this->_db->update_serie_student($students_array[$current_student]->getEmail(),$nb_series);
							$current_student++;
						}
					}
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
					$_SESSION['notification_success']="Vos donn�es ont bien �t� trait�es";
				else 
					$_SESSION['notification_warning']="Vos donn�es ont bien �t� trait�es mais ".$nbDataDuplicated." donn�es �taient d�j� pr�sentes";
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
			die('Erreur de connexion � la base de donn�es : '.
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
		$req = $this->_db->prepare('DELETE FROM series where serie_number=:serie_number and serie_bloc=:serie_bloc');
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
		$req->execute(array('name' => $name,
							'lesson_code' => $lesson_code,
							'quadri' => $quadri,
							'type' => $type,
							'credits' => $credits,
							'abbreviation' => $abbreviation));
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
	
	public function count_serie($serie_bloc){
		$req = $this->_db->prepare("SELECT count(*) as total from series where serie_bloc =:serie_bloc");
		$req->execute(array('serie_bloc' => $serie_bloc));
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
		$req = $this->_db->prepare("SELECT * FROM students WHERE email_student = :email_student");
		$req->execute(array("email_student" => $email));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
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
	
	public function select_lesson_pk($lesson_code)
	{
		$req = $this->_db->prepare("SELECT * FROM lessons WHERE lesson_code = :lesson_code");
		$req->execute(array("lesson_code" => $lesson_code));
		$result = $req->fetch();
		$req->closeCursor();
		return $result;
	}
	
	public function select_serie_pk($serie_number,$serie_bloc)
	{
		$req = $this->_db->prepare("SELECT * FROM series WHERE serie_number = :serie_number AND serie_bloc = :serie_bloc  ");
		$req->execute(array("serie_number" => $serie_number,
							"serie_bloc" => $serie_bloc));
		$result = $req->fetch();
		$req->closeCursor();
		$result = new Serie($result->serie_number,$result->serie_bloc);
		return $result;
	}
	
	public function select_student_serie($serie_number)
	{
		$req = $this->_db->prepare("SELECT * FROM students WHERE serie_number = :serie_number");
		$req->execute(array("serie_number" => $serie_number));
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

}
?>