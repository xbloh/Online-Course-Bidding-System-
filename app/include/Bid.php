<?php

class Bid
{
	private $userid;
    private $amount;
    private $code;
    private $section;
	private $sectionId;


	private $isAddBid;
	private $isCart;
	private $isUpdate;

	
	public function __construct($userid, $amount, $code, $section, $isAddBid = False, $isCart = False, $isUpdate = False)
	{
		$this->userid = $userid;
        $this->amount = $amount;
        $this->code = $code;
		$this->section = $section;
		
		$this->isAddBid = $isAddBid;
		$this->isCart = $isCart;
		$this->isUpdate = $isUpdate;
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

	public function getIsAddBid()
	{
		return $this->isAddBid;
	}

	public function getIsCart()
	{
		return $this->isCart;
	}

	public function getIsUpdate()
	{
		return $this->isUpdate;
	}


	public function validate()
	{

		$errors = [];

		$StudentDAO = new StudentDAO;
		$CourseDAO = new CourseDAO;
		$SectionDAO = new SectionDAO;
		$prerequisiteDAO = new PrerequisiteDAO();
		$courseCompletedDAO = new CoursesCompletedDAO();
		$bidDAO = new BidDAO();
	if($this->isAddBid==FALSE && $this->isCart==FALSE && $this->isUpdate==FALSE){
		if (!$StudentDAO->isUserIdValid($this->userid)){
			$errors[] = "invalid UserId";
		}
		if ($this->amount<10||!(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $this->amount))||$this->amount>999) {
		// if($this->amount<10||$this->amount!=number_format($this->amount,2,'.','')||$this->amount>999){
			$errors[] = "invalid Amount";
		}
		if (!$CourseDAO->isCourseIdExists($this->code)) {
			$errors[] = "invalid Course";
		}
		if (!$SectionDAO->isSectionIdExists($this->section)) {
			$errors[] = "invalid Section";
		}
		if(empty($errors)){
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
		
			$student = $StudentDAO->retrieveStudentByUserId($this->userid);
			// var_dump($student);
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
			// var_dump($bidDAO->numberOfSectionsByID($this->userid));

			$StudentObj=$StudentDAO->retrieveStudentByUserId($this->userid);
			
			$biddedAmt=$bidDAO->totalAmountByID($this->userid);
			$currentAmt=($this->amount);
			$totalAmt=$biddedAmt+$currentAmt;
			if($totalAmt > ($StudentObj->getEdollar())){
				$errors[] = "Not enough e-dollar";
		}
			
	}
	}
	if($this->isAddBid){
		if($bidDAO->isUSerCourseSectionExists($this->userid, $this->code, $this->section)){
			$errors[] = "This section have been bidded";
		}
	}

	if($this->isCart){
		if ($this->amount<10||!(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $this->amount))||$this->amount>999) {
			// if($this->amount<10||$this->amount!=number_format($this->amount,2,'.','')||$this->amount>999){
				$errors[] = "Invalid Amount(more than 10 and les than 999)";
		}

		$student = $StudentDAO->retrieveStudentByUserId($this->userid);
		$school = $student->getSchool();
		$courses = $CourseDAO->retrieveCoursesBySchool($school);

		$courseCompleted = $courseCompletedDAO->retrieveCoursesCompByUserId($this->userid);
		$prerequisiteId = $prerequisiteDAO->retrievePreRequisitesIdByCourseId($this->code);
		foreach($prerequisiteId as $prerequisiteEach){
			if(!in_array($prerequisiteEach, $courseCompleted)){
				$errors[] = "Incomplete Prerequisites  ".$this->code.'  '.$this->section;
			}
		}
		$course=$this->code;
		// var_dump($courseCompleted);
		if(in_array($course, $courseCompleted)){
			$errors[] = "Course completed  ".$course;
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
					$errors[] = "Class timetable clash  ".$this->code."  ".$this->section;
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
					$errors[] = "Exam timetable clash  ".$this->code."  ".$this->section;
				}
			}
		}

	}

	if($this->isUpdate)
	{
		$currentBidDayTime = $SectionDAO->retrieveSectionDayTime($this->code,$this->section);
		$currentBidDate=$currentBidDayTime[0];
		$currentBidStart=$currentBidDayTime[1];
		$currentBidEnd=$currentBidDayTime[2];

		$bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($this->userid);
		foreach ($bidded_modules as $bidded_module){
			$moduleClassDateTime=$SectionDAO->retrieveSectionDayTime($bidded_module[0],$bidded_module[1]);
			if($currentBidDate==$moduleClassDateTime[0]){
				if($moduleClassDateTime[1]<=$currentBidStart || $moduleClassDateTime[2]<=$currentBidEnd){
					$errors[] = "class timetable clash";
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
					$errors[] = "exam timetable clash";
				}
			}
		}

		$student = $StudentDAO->retrieveStudentByUserId($this->userid);
		$school = $student->getSchool();
		$courses = $CourseDAO->retrieveCoursesBySchool($school);

		$courseCompleted = $courseCompletedDAO->retrieveCoursesCompByUserId($this->userid);
		$prerequisiteId = $prerequisiteDAO->retrievePreRequisitesIdByCourseId($this->code);
		foreach($prerequisiteId as $prerequisiteEach){
			if(!in_array($prerequisiteEach, $courseCompleted)){
				$errors[] = "incomplete prerequisites";
			}
		}

		$course=$this->code;
		// var_dump($courseCompleted);
		if(in_array($course, $courseCompleted)){
			$errors[] = "course completed";
		}
	}



	return $errors;
	
		

	}

}
?>
