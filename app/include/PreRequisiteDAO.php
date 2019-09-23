<?php

/**
 * 
 */
class PreRequisiteDAO
{
	
	public function retrievePreRequisites($course)
	{
		$courseId = $course->getCourseId();
		$sql = 'SELECT * from prerequisite where course=:courseid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseid',$courseId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['prerequisite'];
        }
        return $result;
	}
}

?>