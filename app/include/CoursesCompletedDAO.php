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
        $sql = 'ALTER TABLE COURSE_COMPLETED DROP FOREIGN KEY COURSE_COMPLETED_FK1;
        ALTER TABLE COURSE_COMPLETED DROP FOREIGN KEY COURSE_COMPLETED_FK2;
        TRUNCATE TABLE COURSE_COMPLETED;
        ALTER TABLE COURSE_COMPLETED ADD CONSTRAINT COURSE_COMPLETED_FK1 foreign key(userid) references STUDENT(userid);
        ALTER TABLE COURSE_COMPLETED ADD CONSTRAINT COURSE_COMPLETED_FK2 foreign key(code) references COURSE(courseID);';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  
}

?>