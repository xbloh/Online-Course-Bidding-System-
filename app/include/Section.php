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
		private $isBidded;

		function __construct($courseId, $sectionId, $day, $start, $end, $instructor, $venue, $size, $isBidded = False)
		{
			$this->courseId = $courseId;
			$this->sectionId = $sectionId;
			$this->day = $day;
			$this->start = $start;
			$this->end = $end;
			$this->instructor= $instructor;
			$this->venue = $venue;
			$this->size = $size;
			$this->isBidded = $isBidded;
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

		public function placeBid()
		{
			$this->isBidded = True;
		}

		public function isBidded()
		{
			return $this->isBidded;
		}
	}

?>