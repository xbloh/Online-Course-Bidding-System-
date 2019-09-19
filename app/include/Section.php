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

		public function getSectionId()
		{
			return $this->sectionId;
		}
	}

?>