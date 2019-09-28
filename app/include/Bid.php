<?php

class Bid
{
	private $userid;
    private $amount;
    private $code;
    private $section;

	
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
		if (!preg_match('/^(?:[0-9]{0,3})\.\d{2}$/', $this->amount)) {
			$errors[] = "invalid Amount";
		}
		if (!$CourseDAO->isCourseIdExists($this->code)) {
			$errors[] = "invalid Course";
		}
		if (!$SectionDAO->isSectionIdExists($this->sectionId)) {
			$errors[] = "invalid Section";
		}

		$courseCompleted = $courseCompletedDAO->retrieveCoursesCompleted($this->userid);
		$prerequisiteId = $prerequisiteDAO->retrievePreRequisitesId($this->code);
		foreach($prerequisiteId as $prerequisiteEach){
			if(!in_array($prerequisiteEach, $courseCompleted)){
				$errors[] = "Incomplete Prerequisites";
			}
		}

		if(!in_array($this->code, $courseCompleted)){
			$errors[] = "Course completed";
			}

		// $courseIdSectionId = $BidDAO->retrieveCourseIdSecitionIdBidded($this->userid);
		// foreach ($courseIdSectionId as $coursesection){
		// 	$courselist[]+=$coursesection[0];
		// 	$coursesectionlist[]+=$coursesection;
		// }
		// $examdaytimelist=[];
		// foreach($courselist as $course){
		// 	$examdaytimelist=[$bidDAO->retrieveExamDateTime($course)]
		// }
		// $examdate=[];
		// foreach($examdaytimelist as $date){
		// 	if !in_array($date, $examdate){
		// 		$examdate=[$date[0]];
		// 	}
		// }
		
		// foreach($examdate as $date){
		// 	$count=0;
		// 	foreach($examdaytimelist as $datetime){
		// 		if $date==$datetime[0]{
		// 			$count++;
		// 		}
		// 	}
		// }
		


	



		

	}

}

