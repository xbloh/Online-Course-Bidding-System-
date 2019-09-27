<?php
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

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = new Course($row['courseID'], $row['school'], $row['title'], $row['description'], $row['examDate'], $row['examStart'], $row['examEnd']);

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
        $sql = '
        ALTER TABLE COURSE_COMPLETED DROP FOREIGN KEY COURSE_COMPLETED_FK2;
        ALTER TABLE PREREQUISITE DROP FOREIGN KEY PREREQUISITE_FK1;
        ALTER TABLE PREREQUISITE DROP FOREIGN KEY PREREQUISITE_FK2;
        ALTER TABLE BID DROP FOREIGN KEY BID_FK2;
        ALTER TABLE SECTION DROP FOREIGN KEY SECTION_FK1;


        TRUNCATE TABLE COURSE;

        ALTER TABLE SECTION ADD CONSTRAINT SECTION_FK1 foreign key(courseID) references COURSE(courseID)
        ALTER TABLE BID ADD CONSTRAINT BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID);
        ALTER TABLE PREREQUISITE ADD CONSTRAINT PREREQUISITE_FK1 foreign key(course) references COURSE(courseID);
        ALTER TABLE PREREQUISITE ADD CONSTRAINT PREREQUISITE_FK2 foreign key(prerequisite) references COURSE(courseID);
        ALTER TABLE COURSE_COMPLETED ADD CONSTRAINT COURSE_COMPLETED_FK2 foreign key(code) references COURSE(courseID);';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  

    public function isCourseIdExists($courseId)
    {
        $sql = 'SELECT * from course where courseID = :courseId';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Step 4 - Retrieve Query Results (if any)
        return $row=$stmt->fetch();
    }

    public function retrieveExamDateTime($courseId)
    {
        $sql = 'SELECT * from course where courseID = :courseId';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = new Course($row['examDate'], $row['examStart'], $row['examEnd']);

        return $result;
    }
}

?>