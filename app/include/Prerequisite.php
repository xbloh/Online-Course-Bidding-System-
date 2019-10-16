<?php

class Prerequisite
{
	private $course;
	private $prerequisite;

	
	public function __construct($course, $prerequisite)
	{
		$this->course = $course;
		$this->prerequisite = $prerequisite;
	}

	public function getCourse()
	{
		return $this->course;
	}

	public function getPrerequisite()
	{
		return $this->prerequisite;
	}

	public function validate()
	{
		$errors = [];

		$courseDAO = new CourseDAO();
		if (!$courseDAO->isCourseIdExists($this->course)) {
			$errors[] = "invalid course";
		}

		if (!$courseDAO->isCourseIdExists($this->prerequisite)) {
			$errors[] = "invalid prerequisite";
		}

		return $errors;
	}
}

