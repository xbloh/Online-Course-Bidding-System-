<?php

	/**
	 * 
	 */
	class Section
	{
		private $course;
		private $sectionId;
		private $day;
		private $start;
		private $end;
		private $instructor;
		private $venue;
		private $size;
		private $isBidded;

		function __construct($course, $sectionId, $day, $start, $end, $instructor, $venue, $size, $isBidded = False)
		{
			$this->course = $course;
			$this->sectionId = $sectionId;
			$this->day = $day;
			$this->start = $start;
			$this->end = $end;
			$this->instructor= $instructor;
			$this->venue = $venue;
			$this->size = $size;
			$this->isBidded = $isBidded;
		}

		public function getCourse()
		{
			return $this->course;
		}

		public function getSectionId()
		{
			return $this->sectionId;
		}

		public function getDay()
		{
			return $this->day;
		}

		public function getStart()
		{
			return $this->start;
		}

		public function getEnd()
		{
			return $this->end;
		}

		public function getInstructor()
		{
			return $this->instructor;
		}

		public function getVenue()
		{
			return $this->venue;
		}

		public function getSize()
		{
			return $this->size;
		}

		public function placeBid()
		{
			$this->isBidded = True;
		}

		public function isBidded()
		{
			return $this->isBidded;
		}

		public function validate()
		{
			$errors = [];

			$courseDAO = new CourseDAO();
			if (!$courseDAO->isCourseIdExists($this->course)) {
				$errors[] = "invalid course";
				return $errors;
			}
			// !(preg_match('/^(?:[s][1-9]\d{0,99})$/', $this->sectionId))
			// if (substr($this->sectionId, 0, 1) !== "S"|| substr($this->sectionId, 1, 2) == 0 || !is_numeric(substr($this->sectionId, 1)) || substr($this->sectionId, 1) > 99 || substr($this->sectionId, 1) < 1) {
				if (!(preg_match('/^(?:[sS][1-9]\d{0,99})$/', $this->sectionId)) || !is_numeric(substr($this->sectionId, 1)) || substr($this->sectionId, 1) > 99 || substr($this->sectionId, 1) < 1) {
				$errors[] = "invalid section";
			}


			if ((int)$this->day > 7 || (int)$this->day < 1) {
				$errors[] = "invalid day";
			}

			$start = $this->start;
			if (!preg_match("/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $start)) {
				$errors[] = "invalid start";
			}


			$end = $this->end;
			if (!preg_match("/^(?:2[0-3]|[01]?[0-9]):[0-5][0-9]$/", $end)) {
				$errors[] = "invalid end";
			} else if (strtotime($this->end) <= strtotime($this->start)) {
				$errors[] = "invalid end";
			}

			if (strlen($this->instructor) > 100) {
				$errors[] = "invalid instructor";
			}

			if (strlen($this->venue) > 100) {
				$errors[] = "invalid venue";
			}

			if ((int)$this->size < 1) {
				$errors[] = "invalid size";
			}

			return $errors;
			
		}
	}

?>