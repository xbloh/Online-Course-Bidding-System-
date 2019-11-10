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
	private $coursesCompleted;

	public function __construct($userid, $password, $name, $school, $edollar)
	{
		$this->userid = $userid;
		$this->password = $password;
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

	public function getPassword()
	{
		return $this->password;
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

	public function validate()
	{
		$errors = [];

		if (strlen($this->userid) > 128) {
			$errors[] = "invalid userid";
		}

		$studentDAO = new StudentDAO();
		if ($studentDAO->isUserIdValid($this->userid)) {
			$errors[] = "duplicate userid";
		}

		if ($this->edollar<0||!(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $this->edollar))||$this->edollar>999) {
		// if($this->edollar!=number_format($this->edollar,2,'.','')){
		// if($this->edollar<10||$this->edollar!=number_format($this->edollar,2,'.','')||$this->edollar>999){
			$errors[] = "invalid e-dollar";
		}

		if (strlen($this->password) > 128) {
			$errors[] = "invalid password";
		}

		if (strlen($this->name) > 100) {
			$errors[] = "invalid name";
		}

		return $errors;
	}
}

?>