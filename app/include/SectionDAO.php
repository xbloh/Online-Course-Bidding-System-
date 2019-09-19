<?php

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
	            $result[] = new Section($row['courseID'], $row['sectionID'], $row['day'], $row['start'], $row['end'], $row['instructor'], $row['venue'], $row['size']);
	        }
	        return $result;
		}
	}

?>