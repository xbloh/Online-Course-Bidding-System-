<?php

/**
 * 
 */
class BidDAO
{
	
	function placeBid($bid)
	{
		//put bid into database!
		$student = $_SESSION['student'];
		$userId = $student->getUserId();
		$section = $bid->section;
		$course = $section->getCourse();
		$courseId = $course->getCourseId();
		$sectionId = $section->getSectionId();

		$sql = 'INSERT into bid values (:userId, :amount, :courseId, :sectionId)';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':amount',$amount,PDO::PARAM_STR);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
	}
}

?>