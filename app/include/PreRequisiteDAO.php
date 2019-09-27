<?php
require_once 'common.php';
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

        $courseDAO = new CourseDAO();
        $courses = [];
        foreach ($result as $code) {
        	$course = $courseDAO->retrieveCourseById($code);
        	$courses[] = $course;
        }

        return $courses;
    }

    public function retrievePreRequisitesId($course)
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

    public function add($prerequisite){

        $sql = 'INSERT INTO PREREQUISITE (course, prerequisite)
                VALUES (:course, :prerequisite)
                ';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql); 
        $course = $prerequisite->getCourse();
        $prerequisite = $prerequisite->getPrerequisite();
        
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':prerequisite', $prerequisite, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    public function removeAll() {
        $sql = '
        TRUNCATE TABLE PREREQUISITE;
        ';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  
}

?>