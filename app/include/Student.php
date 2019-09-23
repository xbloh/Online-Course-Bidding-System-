<?php

/**
 * 
 */
class Student
{
	private $userid;
	private $name;
	private $school;
	private $edollar;
	private $coursesCompleted;

	public function __construct($userid, $name, $school, $edollar)
	{
		$this->userid = $userid;
		$this->name = $name;
		$this->school = $school;
		$this->edollar = $edollar;
		
	}

	public function getUserId()
	{
		return $this->userid;
	}

	public function getEdollarAfterBid($edollarUsed)
	{
		$this->edollar -= $edollarUsed;
		return $this->edollar;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSchool()
	{
		return $this->school;
	}

	public function getEdollar()
	{
		return $this->edollar;
	}

	public function setCoursesCompleted($coursesCompleted)
	{
		$this->coursesCompleted = $coursesCompleted;
	}

	public function getCoursesCompleted()
	{
		return $this->coursesCompleted;
	}
}

?>