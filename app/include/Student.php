<?php

/**
 * 
 */
class Student
{
	private $userid;
	private $password;
	private $name;
	private $school;
	private $edollar;

	public function __construct($userid, $name, $school, $edollar)
	{
		$this->userid = $userid;
		$this->name = $name;
		$this->school = $school;
		$this->edollar = $edollar;
		
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
}

?>