<?php
require_once 'common.php';
//require_once 'include/protect.php';
/**
 * 
 */
class PrerequisiteDAO
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
        var_dump($courseId);
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

    public function retrievePreRequisitesById($courseId)
    {
        // var_dump($courseId);
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

    public function retrievePreRequisitesIdByCourseId($courseId)
    {
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
        // $sql = 'TRUNCATE TABLE PREREQUISITE';
        $sql = 'delete from PREREQUISITE';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  

    public function isCourseRequirePrerequisite($courseId)
    {
        $sql = 'SELECT count(*) as countCourse from PREREQUISITE where course = :courseId';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $row=$stmt->fetch();
        
        $existOK=FALSE;
        if($row['countCourse']>0){
            $existOK=TRUE;
        }
        return $existOK;

        $stmt = null;
        $pdo = null;
    }

    public function dump()
    {
        $sql = 'SELECT * from prerequisite order by course, prerequisite';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['course' => $row['course'], 'prerequisite' => $row['prerequisite']];
        }
        return $result;
    }
}

?>