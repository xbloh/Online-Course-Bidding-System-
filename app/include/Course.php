<?php

/**
 * 
 */
class Course
{
	private $courseId;
	private $school;
	private $title;
	private $description;
	private $examDate;
	private $examStart;
	private $examEnd;
	private $sectionsAvailable;
	private $preRequisites;
	
	public function __construct($courseId, $school, $title, $description, $examDate, $examStart, $examEnd)
	{
		$this->courseId = $courseId;
		$this->school = $school;
		$this->title = $title;
		$this->description = $description;
		$this->examDate = $examDate;
		$this->examStart = $examStart;
		$this->examEnd = $examEnd;
	}

	public function getCourseId()
	{
		return $this->courseId;
	}

	public function getSchool()
	{
		return $this->school;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getExamDate()
	{
		return $this->examDate;
	}

	public function getExamStart()
	{
		return $this->examStart;
	}

	public function getExamEnd()
	{
		return $this->examEnd;
	}

	public function setSectionsAvailable($sectionsAvailable)
	{
		$this->sectionsAvailable = $sectionsAvailable;
	}

	public function getSectionsAvailable()
	{
		return $this->sectionsAvailable;
	}

	public function setPreRequisites($preRequisites)
	{
		$this->preRequisites = $preRequisites;
	}

	public function validate()
	{
		$errors = [];

		if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->examDate)) {
			$errors[] = "invalid exam date";
		}

		$examStart = explode(":", $this->examStart);
		if ((int)$examStart[0] > 23 || (int)$examStart[1] > 59 || (int)$examStart[0] < 0 || (int)$examStart[1] < 0) {
			$errors[] = "invalid exam start";
		}


		$examEnd = explode(":", $this->examEnd);
		if ((int)$examEnd[0] > 23 || (int)$examEnd[1] > 59 || (int)$examEnd[0] < 0 || (int)$examEnd[1] < 0) {
			$errors[] = "invalid exam end";
		} else if (strtotime($this->examEnd) <= strtotime($this->examStart)) {
			$errors[] = "invalid exam end";
		}

		if (strlen($this->title) > 100) {
			$errors[] = "invalid title";
		}

		if (strlen($this->description) > 1000) {
			$errors[] = "invalid description";
		}

		return $errors;

	}

}

