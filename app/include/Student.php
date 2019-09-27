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
		if ($studentDAO->isUserIdExists($this->userid)) {
			$errors[] = "duplicate userid";
		}

		// check for invalid e-dollar


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