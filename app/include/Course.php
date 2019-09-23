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
	private $preRequisite;
	
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

}

