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

		if (!$StudentDAO->isUserIdValid($this->userid)){
			$errors[] = "invalid UserId";
		}
		// if (!preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $this->amount)) {
		if(!number_format($this->amount,2,'.','')){
			$errors[] = "invalid Amount";
		}
		if (!$CourseDAO->isCourseIdExists($this->code)) {
			$errors[] = "invalid Course";
		}
		if (!$SectionDAO->isSectionIdExists($this->section)) {
			$errors[] = "invalid Section";
		}

		$student = $StudentDAO->retrieveStudentByUserId($this->userid);
		$school = $student->getSchool();
		$courses = $CourseDAO->retrieveCoursesBySchool($school);

		$courseCompleted = $courseCompletedDAO->retrieveCoursesCompByUserId($this->userid);
		$prerequisiteId = $prerequisiteDAO->retrievePreRequisitesIdByCourseId($this->code);
		foreach($prerequisiteId as $prerequisiteEach){
			if(!in_array($prerequisiteEach, $courseCompleted)){
				$errors[] = "Incomplete Prerequisites";
			}
		}
		$course=$this->code;
		// var_dump($courseCompleted);
		if(in_array($course, $courseCompleted)){
			$errors[] = "Course completed";
		}
		// var_dump($bidDAO->numberOfSectionsByID($this->userid));
		if($bidDAO->numberOfSectionsByID($this->userid)>=5){
			$errors[] = "Section limit reached";
		}

		$StudentObj=$StudentDAO->retrieveStudentByUserId($this->userid);
		$totalAmt=$bidDAO->totalAmountByID($this->userid)+$this->amount;
		if($bidDAO->totalAmountByID($this->userid) > $StudentObj->getEdollar()){
			$errors[] = "Not enough e-dollar";
		}
		

		$currentBidDayTime = $SectionDAO->retrieveSectionDayTime($this->code,$this->section);
		// var_dump($currentBidDayTime);
		$currentBidDate=$currentBidDayTime[0];
		$currentBidStart=$currentBidDayTime[1];
		$currentBidEnd=$currentBidDayTime[2];

		$bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($this->userid);
		foreach ($bidded_modules as $bidded_module){
			$moduleClassDateTime=$SectionDAO->retrieveSectionDayTime($bidded_module[0],$bidded_module[1]);
			if($currentBidDate==$moduleClassDateTime[0]){
				if($moduleClassDateTime[1]<=$currentBidStart||$moduleClassDateTime[2]<=$currentBidEnd){
					$errors[] = "Class timetable clash";
				}
			}
		}

		$currentBidDayTime = $CourseDAO->retrieveExamDateTime($this->code);
		$currentBidDate=$currentBidDayTime[0];
		$currentBidStart=$currentBidDayTime[1];
		$currentBidEnd=$currentBidDayTime[2];

		$bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($this->userid);
		foreach ($bidded_modules as $bidded_module){
			$moduleExamDateTime=$CourseDAO->retrieveExamDateTime($bidded_module[0]);
			if($currentBidDate==$moduleExamDateTime[0]){
				if($moduleExamDateTime[1]<=$currentBidStart||$moduleExamDateTime[2]>=$currentBidEnd){
					$errors[] = "Exam timetable clash";
				}
			}
		}

		return $errors;
		// $biddedSectionDayTime = [];
		// foreach ($courseIdSectionId as $coursesection) //bidded section [day and time]
		// {
		// 	$courseId = $coursesection[0];
		// 	$sectionId = $coursesection[1];
		// 	$sectionDayTime = $sectionDAO->retrieveSectionDayTime($courseId,$sectionId);
		// 	$biddedSectionDayTime[] += $sectionDayTime;
		// }

		// //bidding section [day and time]
		// $section = $sectionDAO->retrieveSectionDayTime($this->code,$this->section);
		// $biddingSectionDay = $section->getDay();
		// $biddingSectionStart = $section->getStart();
		// $biddingSectionEnd = $section->getEnd();

		// foreach($biddedSectionDayTime as $biddedSection)
		// {
		// 	$biddedSectionDay = $section[0];
		// 	$biddedSectionStart = $section[1];
		// 	$biddedSectionEnd = $section[2];

		// 	if($biddedSectionDay == $biddingSectionDay)
		// 	{
		// 		if($biddedSectionStart >= $biddingSectionStart)
		// 		{
		// 			if($biddedSectionEnd <= $biddingSectionEnd)
		// 			{
		// 				$errors[] = "Class timetable clash";
		// 			}
		// 		}
		// 	}
		// }



		// if()
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
