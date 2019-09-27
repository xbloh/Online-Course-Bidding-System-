<?php

class Course_Completed
{
	private $userid;
    private $code;
	
	public function __construct($userid, $code)
	{
		$this->userid = $userid;
        $this->code = $code;
	}

	public function getUserId()
	{
		return $this->userid;
	}

    public function getCode()
	{
		return $this->code;
    }

    public function validate()
    {

    	$errors = [];
        $studentDAO = new StudentDAO();
        $courseDAO = new CourseDAO();
        $prerequisitesDAO = new PreRequisiteDAO();
        $coursesCompletedDAO = new CoursesCompletedDAO();

    	if (!$studentDAO->isUserIdExists($this->userid)) {
    		$errors[] = "invalid userid";
    	}

    	if (!$courseDAO->isCourseIdExists($this->code)) {
    		$errors[] = "invalid course";
    	}

    	$course = $courseDAO->retrieveCourseById($this->code);
    	$prerequisites = $prerequisitesDAO->retrievePreRequisitesId($course);
    	$student = $studentDAO->retrieveStudentByUserId($this->userid);
    	$coursesCompleted = $coursesCompletedDAO->retrieveCourseIdCompleted($student);
    	foreach ($prerequisites as $item) {
    		if (!in_array($item, $coursesCompleted)) {
    			$errors[] = "invalid course completed";
    		}
    	}
    }

}

