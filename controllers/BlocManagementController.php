<?php
class BlocManagementController{
	private $_db;

	public function __construct($db) {
		$this->_db = $db;
	}

	public function run(){
		var_dump($_POST);
		if(isset($_POST['lessonType'])){
			if($_POST['lessonType']=="LearningActivity")
				$this->_db->insertLesson($_POST['lessonName'], "LA", $_POST['lessonQuad'], $_POST['lessonCred'], $_POST['lessonAb']);
			if($_POST['lessonType']=="CourseUnit")
				$this->_db->insertLesson($_POST['lessonName'], "CU", $_POST['lessonQuad'], $_POST['lessonCred'], $_POST['lessonAb']);
		}
		require_once(PATH_VIEW . 'blocManager.php');
	}
}
?>