<?php
require_once 'common.php';
	/**
	 * 
	 */
	class SectionDAO
	{
		
		public function retrieveSectionsByCourse($course)
		{
			$courseId = $course->getCourseId();

			$sql = 'SELECT * from section where courseID=:courseId';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
	        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();


	        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	            $result[] = new Section($course, $row['sectionID'], $row['day'], $row['start'], $row['end'], $row['instructor'], $row['venue'], $row['size']);
	        }
	        return $result;
		}
		public function add($section){

			$sql = 'INSERT INTO SECTION (courseID, sectionID, day, start, end, instructor, venue, size)
					VALUES (:courseID, :sectionID, :day, :start, :end, :instructor, :venue, :size)
					';

			$connMgr = new ConnectionManager();       
			$conn = $connMgr->getConnection();
			
			$stmt = $conn->prepare($sql); 
			$courseId = $section->getCourse();
			$sectionId = $section->getSectionId();
			$day = $section->getDay();
			$start = $section->getStart();
			$end = $section->getEnd();
			$instructor = $section->getInstructor();
			$venue = $section->getVenue();
			$size = $section->getSize();

			$stmt->bindParam(':courseID', $courseId, PDO::PARAM_STR);
			$stmt->bindParam(':sectionID', $sectionId, PDO::PARAM_STR);
			$stmt->bindParam(':day', $day, PDO::PARAM_INT);
			$stmt->bindParam(':start', $start, PDO::PARAM_STR);
			$stmt->bindParam(':end', $end, PDO::PARAM_STR);
			$stmt->bindParam(':instructor', $instructor, PDO::PARAM_STR);
			$stmt->bindParam(':venue', $venue, PDO::PARAM_STR);
			$stmt->bindParam(':size', $size, PDO::PARAM_INT);
			
			$isAddOK = False;
			if ($stmt->execute()) {
				$isAddOK = True;
			}
	
			return $isAddOK;
			//($courseId, $sectionId, $day, $start, $end, $instructor, $venue, $size)
		}

		public function removeAll(){
			$sql = '
        	#ALTER TABLE BID DROP FOREIGN KEY BID_FK2;

			TRUNCATE TABLE SECTION;

        	#ALTER TABLE BID ADD CONSTRAINT BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID);';
			$connMgr = new ConnectionManager();
			$conn = $connMgr->getConnection();
	
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$count = $stmt->rowCount();
			
		}

		public function isSectionIdExists($sectionId)
	    {

	      $sql = 'SELECT * from section where sectionID=:sectionId';
	        
	          $connMgr = new ConnectionManager();      
	          $conn = $connMgr->getConnection();

	          $stmt = $conn->prepare($sql);
	      $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
	      
	          $stmt->setFetchMode(PDO::FETCH_ASSOC);
	          $stmt->execute();

	      return $row=$stmt->fetch();
	    }
	}

?>