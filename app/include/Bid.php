<?php

class Bid
{
	private $userid;
    private $amount;
    private $code;
    private $section;
    private $sectionId;

	
	public function __construct($userid, $amount, $code, $section)
	{
		$this->userid = $userid;
        $this->amount = $amount;
        $this->code = $code;
        $this->section = $section;
    }
    
	public function getUserid()
	{
		return $this->userid;
    }
    
	public function getAmount()
	{
		return $this->amount;
    }

    public function getCode()
	{
		return $this->code;
    }

    public function getSection()
	{
		return $this->section;
	}

	public function validate()
	{

		$errors = [];

		$StudentDAO = new StudentDAO;
		$CourseDAO = new CourseDAO;
		$SectionDAO = new SectionDAO;
		$prerequisiteDAO = new PreRequisiteDAO();
		$courseCompletedDAO = new CoursesCompletedDAO();
		$bidDAO = new BidDAO();

		if (!$StudentDAO->isUserIdExists($this->userid)){
			$errors[] = "invalid UserId";
		}
		if (!preg_match('/^(?:[0-9]{0,3})\.\d{0,2}$/', $this->amount)) {
			$errors[] = "invalid Amount";
		}
		if (!$CourseDAO->isCourseIdExists($this->code)) {
			$errors[] = "invalid Course";
		}
		if (!$SectionDAO->isSectionIdExists($this->section)) {
			$errors[] = "invalid Section";
		}

		$courseCompleted = $courseCompletedDAO->retrieveCoursesCompletedByUserId($this->userid);
		$prerequisiteId = $prerequisiteDAO->retrievePreRequisitesIdByCourseId($this->code);
		foreach($prerequisiteId as $prerequisiteEach){
			if(!in_array($prerequisiteEach, $courseCompleted)){
				$errors[] = "Incomplete Prerequisites";
			}
		}

		if(!in_array($this->code, $courseCompleted)){
			$errors[] = "Course completed";
		}

		if($BidDAO->numberOfSectionsByID($this->userid)>=5){
			$errors[] = "Section limit reached";
		}

		// $StudentObj=$StudentDAO->retrieveStudentByUserId($this->userid);
		// if($BidDAO->totalAmountByID($this->userid) > $StudentObj->getAmount()){
		// 	$errors[] = "Not enough e-dollar";
		// }

		$courseIdSectionId = $BidDAO->retrieveCourseIdSecitionIdBidded($this->userid);
		foreach ($courseIdSectionId as $coursesection) {
		$courselist[]+=$coursesection[0];
		$coursesectionlist[]+=$coursesection;
		}
		$examdaytimelistBidded = [];
		foreach($courselist as $course) {
			$examdaytimelistBidded = [$bidDAO->retrieveExamDateTime($course)];
		}
		$examdate = [];
		$examStart = [];
		$examEnd = [];
		foreach($examdaytimelistBidded as $datetime) {
			if(!in_array($datetime, $examdate)) { //Bidded examDate in list - examdate
				$examdate = [$datetime[0]];		  
				$examStart = [$datetime[1]];	  //Bidded examStart in list - examStart
				$examEnd = [$datetime[2]];		  //Bidded examEnd in list - examEnd
			}
		}

		//check date first then check start then check end 
		// foreach ($examdate as $date)
		// {
		// 	if(in_array($date));
		// }


		// foreach($examdate as $date){
		// $count = 0;
		// 	foreach($examdaytimelist as $datetime){
		// 		if ($date==$datetime[0]) {
		// 			$count++;
		// 		}
		// 	}
		// }
	
		

	}

}
?>
