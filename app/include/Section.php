<?php

	/**
	 * 
	 */
	class Section
	{
		private $courseId;
		private $sectionId;
		private $day;
		private $start;
		private $end;
		private $instructor;
		private $venue;
		private $size;

		function __construct($courseId, $sectionId, $day, $start, $end, $instructor, $venue, $size)
		{
			$this->courseId = $courseId;
			$this->sectionId = $sectionId;
			$this->day = $day;
			$this->start = $start;
			$this->end = $end;
			$this->instructor= $instructor;
			$this->venue = $venue;
			$this->size = $size;
		}

		public function getCourseId()
		{
			return $this->courseId;
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
	}

?>