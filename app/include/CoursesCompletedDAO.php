<?php
require_once 'common.php';
/**
 * 
 */
class CoursesCompletedDAO
{
	
	public function retrieveCoursesCompleted($student)
	{
		$userid = $student->getUserId();
		$sql = 'SELECT * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['code'];
        }

        $courseDAO = new CourseDAO();
        $courses = [];
        foreach ($result as $code) {
        	$course = $courseDAO->retrieveCourseById($code);
        	$courses[] = $course;
        }
        return $courses;
    }

    public function retrieveCoursesCompletedByUserId($userId)
    {
        $sql = 'SELECT * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['code'];
        }

        $courseDAO = new CourseDAO();
        $courses = [];
        foreach ($result as $code) {
            $course = $courseDAO->retrieveCourseById($code);
            $courses[] = $course;
        }
        return $courses;
    }

    public function retrieveCoursesCompByUserId($userId)
    {
        $sql = 'SELECT * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result[] = $row['code'];

        return $result;
    }

    public function retrieveCourseIdCompleted($student)
    {
        $userid = $student->getUserId();
        $sql = 'SELECT * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['code'];
        }

        return $result;
    }

    public function add($course_completed){
        $sql = "INSERT INTO COURSE_COMPLETED (userid, code) VALUES (:userid, :code)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $userid = $course_completed->getUserId();
        $code = $course_completed->getCode();

        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);

        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;

    }
    public function removeAll() {
        // $sql = 'TRUNCATE TABLE COURSE_COMPLETED';
        $sql = 'delete from COURSE_COMPLETED';

        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  

    public function isPrerequisiteCompleted($prerequisiteId)
    {
        $sql = 'SELECT count(*) as countPrerequisite from COURSE_COMPLETED where code = :prerequisiteId';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':prerequisiteId',$prerequisiteId,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $row=$stmt->fetch();
        
        $existOK=FALSE;
        if($row['countPrerequisite']>0){
            $existOK=TRUE;
        }
        return $existOK;

        $stmt = null;
        $pdo = null;
    }

    public function dump()
    {
        $sql = 'SELECT * from course_completed order by code, userid';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['userid' => $row['userid'], 'course' => $row['code']];
        }
        return $result;
    }
}

?>