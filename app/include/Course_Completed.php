<?php
//require_once 'include/protect.php';
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
        $prerequisitesDAO = new PrerequisiteDAO();
        $coursesCompletedDAO = new CoursesCompletedDAO();

    	if (!$studentDAO->isUserIdValid($this->userid)) {
    		$errors[] = "invalid userid";
    	}

    	if (!$courseDAO->isCourseIdExists($this->code)) {
    		$errors[] = "invalid course";
    	}

		if($prerequisitesDAO->isCourseRequirePrerequisite($this->code)){
			$prerequisiteId = $prerequisitesDAO->retrievePreRequisitesIdByCourseId($this->code);
			foreach($prerequisiteId as $prerequisiteEach){
				if(!$coursesCompletedDAO->isPrerequisiteCompleted($prerequisiteEach)){
					$errors[] = "invalid course completed";
			}
		}

		}
		
		return $errors;
		
    }

}

