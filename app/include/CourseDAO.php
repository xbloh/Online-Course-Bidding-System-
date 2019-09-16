<?php

/**
 * 
 */
class CourseDAO
{
	
	public function getCourseIdBySchool($school)
	{
		$sql = 'SELECT * from course where school=:school';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Course($row['courseID'], $row['school'], $row['title'], $row['description'], $row['examDate'], $row['examStart'], $row['examEnd']);
        }
        return $result;
	}

	public function retrieveAllCourseId()
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
}

?>