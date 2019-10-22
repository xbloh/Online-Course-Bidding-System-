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

		public function retrieveSectionIdsByCourse($course)
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
	            $result[] = $row['sectionID'];
	        }
	        return $result;
		}

		public function retrieveSectionIds($courseId)
		{
			$sql = 'SELECT sectionID from section where courseID=:courseId';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
	        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();


	        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	            $result[] = $row['sectionID'];
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
			// $sql = '
        	// #ALTER TABLE BID DROP FOREIGN KEY BID_FK2;

			// TRUNCATE TABLE SECTION;

			// #ALTER TABLE BID ADD CONSTRAINT BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID);';
			$sql = 'delete from SECTION';
			$connMgr = new ConnectionManager();
			$conn = $connMgr->getConnection();
	
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$count = $stmt->rowCount();
			
		}

		public function isSectionIdExists($courseId, $sectionId)
	    {

			$sql = 'SELECT COUNT(*) as countSection from section where courseID = :courseId and sectionID=:sectionId';
				
			$connMgr = new ConnectionManager();      
			$conn = $connMgr->getConnection();

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
			$stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
			
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();

			$row=$stmt->fetch();
			$existOK=FALSE;
			if($row['countSection']>0){
				$existOK=TRUE;
			}
			return $existOK;

			$stmt = null;
			$pdo = null;
		}
		
		public function retrieveSectionDayTime($courseId,$sectionId)
		{

			$sql = 'SELECT * from section where courseID=:courseId and sectionID=:sectionId';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
			$stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();

	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        $result = [$row['day'], $row['start'], $row['end']];
	        return $result;
		}

		public function retrieveSection($courseId, $sectionId)
		{
			$sql = 'SELECT * from section where courseID=:courseId and sectionID=:sectionId';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
			$stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();

	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        $result = new Section($courseId, $row['sectionID'], $row['day'], $row['start'], $row['end'], $row['instructor'], $row['venue'], $row['size']);
	        return $result;
		}


		public function retrieveSectionSize($courseId,$sectionId)
		{

			$sql = 'SELECT size from section where courseID=:courseId and sectionID=:sectionId';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
			$stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
			$stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();

	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        $result = $row['size'];
	        return $result;
		}

		public function dump()
	    {
	        $sql = 'SELECT * from section order by courseID, sectionID';

	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();

	        $stmt = $conn->prepare($sql);
	        $stmt->setFetchMode(PDO::FETCH_ASSOC);
	        $stmt->execute();

	        $result = array();


	        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	            $result[] = ['course' => $row['courseID'], 'section' => $row['sectionID'], 'day' => $row['day'], 'start' => $row['start'], 'start' => $row['start'], 'end' => $row['end'], 'instructor' => $row['instructor'], 'venue' => $row['venue'], 'size' => $row['size']];
	        }
	        return $result;
		}
		
		// public function addSize($courseId, $sec)
	    // {
		// 	$currentSize = $this->retrieveStudentByUserId($userid)->getEdollar();
		// 	$total = $current + $toAdd;
		// 	$connMgr = new ConnectionManager();
		// 	$pdo = $connMgr->getConnection();

		// 	// Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
		// 	$sql = "UPDATE student set edollar = :total where userid = :userid
		// 			";
		// 	$stmt = $pdo->prepare($sql);
		// 	$stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
		// 	$stmt->bindParam(':total',$total,PDO::PARAM_STR);
			
		// 	// Step 3 - Execute SQL Query
		// 	$status = $stmt->execute();
		// 	//echo $status;
		// }

		public function updateSize($courseId, $sectionId, $newSize) {
			$sql='UPDATE section SET size=:newsize WHERE courseID=:courseid AND sectionID=:sectionid';
	
			$connMgr = new ConnectionManager();       
			$conn = $connMgr->getConnection();
	
			$stmt = $conn->prepare($sql); 
	
			$stmt->bindParam(':newsize', $newSize, PDO::PARAM_STR);
			$stmt->bindParam(':courseid', $courseId, PDO::PARAM_STR);
			$stmt->bindParam(':sectionid', $sectionId, PDO::PARAM_STR);
		
			$status=FALSE;
			
			if($stmt->execute()){
				$status=TRUE;
			}
			$stmt = null;
			$pdo = null;
			return $status;
		}

	}

?>