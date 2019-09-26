<?php
require_once 'common.php';
/**
 * 
 */
class CourseDAO
{
	
	public function retrieveCoursesBySchool($school)
	{
		$sql = 'SELECT * from course where school=:school';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':school',$school,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Course($row['courseID'], $row['school'], $row['title'], $row['description'], $row['examDate'], $row['examStart'], $row['examEnd']);
        }
        return $result;
	}

	public function retrieveAllCourses()
	{
		$sql = 'SELECT * from course';

		$connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Course($row['courseID'], $row['school'], $row['title'], $row['description'], $row['examDate'], $row['examStart'], $row['examEnd']);;
        }
        return $result;
	}

    public function retrieveCourseById($courseId)
    {
        $sql = 'SELECT * from course where courseID = :courseId';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result = new Course($row['courseID'], $row['school'], $row['title'], $row['description'], $row['examDate'], $row['examStart'], $row['examEnd']);
        }
        return $result;
    }
    public function add($course){
        $sql = "INSERT INTO COURSE (courseId, school, title, description, examDate, examStart, examEnd) VALUES (:courseId, :school, :title, :description, :examDate, :examStart, :examEnd)";

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        $courseId = $course->getCourseId();
        $school = $course->getSchool();
        $title = $course->getTitle();
        $description = $course->getDescription();
        $examDate = $course->getExamDate();
        $examStart = $course->getExamStart();
        $examEnd = $course->getExamEnd();

        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':school', $school, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':examDate', $examDate, PDO::PARAM_STR);
        $stmt->bindParam(':examStart', $examStart, PDO::PARAM_STR);
        $stmt->bindParam(':examEnd', $examEnd, PDO::PARAM_STR);
        
        //$courseId, $school, $title, $description, $examDate, $examStart, $examEnd)
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }


    public function removeAll() {
        $sql = 'TRUNCATE TABLE COURSE';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  
}

?>