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

	public function validate() {
	    $StudentDAO = new StudentDAO;
	    $CourseDAO = new CourseDAO;
	    $SectionDAO = new SectionDAO;

	    if (!$StudentDAO->isUserIdExists($this->userid)){
	      $errors[] = "invalid UserId";
	    }
	    if (!preg_match('/^(?:[0-9]{0,3})\.\d{2}$/', $this->amount)) {
	      $errors[] = "invalid Amount";
	    }
	    if (!$CourseDAO->isCourseIdExists($this->code)) {
	      $errors[] = "invalid Course";
	    }	
	    if (!$SectionDAO->isSectionIdExists($this->section)) {
	      $errors[] = "invalid Section";
	    }

  }
}

