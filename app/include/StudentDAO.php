<?php 
require_once 'common.php';

class StudentDAO{
    

    public function authenticate ($userid, $password){
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * 
                FROM STUDENT 
                WHERE 
                    userid=:userid
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Step 4 - Retrieve Query Results (if any)
        if ($row=$stmt->fetch()){
            if ($password==$row['password']){
                $return_message= 'SUCCESS';
            }else{
                $return_message='Incorrect Password!';
            }
        }else{
            $return_message='Invalid Username!';
        }
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        $student = new Student($row['userid'], $row['password'], $row['name'], $row['school'], $row['edollar']);

        // Step 6 - Return (if any)
        return [$return_message, $student];
    }
    public function add($student){
        $sql = "INSERT INTO STUDENT (userid, password, name, school, edollar) VALUES (:userid, :password, :name, :school, :edollar)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $password = $student->getPassword();
        $userid = $student->getUserId();
        $name = $student->getName();
        $school = $student->getSchool();
        $edollar = $student->getEdollar();

        //$password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':school', $school, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $edollar, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;

    }

    public function removeAll() {
        // $sql = 'SET foreign_key_checks = 0; TRUNCATE TABLE STUDENT; SET foreign_key_checks = 1';
        // $sql = 'ALTER TABLE BID DROP FOREIGN KEY BID_FK1;
        // ALTER TABLE COURSE_COMPLETED DROP FOREIGN KEY COURSE_COMPLETED_FK1;

        // TRUNCATE TABLE STUDENT;

        // ALTER TABLE COURSE_COMPLETED ADD CONSTRAINT COURSE_COMPLETED_FK1 foreign key(userid) references STUDENT(userid);
        // ALTER TABLE BID ADD CONSTRAINT BID_FK1 foreign key(userid) references STUDENT(userid);'

        // ;
        $sql='TRUNCATE TABLE STUDENT';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        // $count = $stmt->rowCount();
    }  

    public function isUserIdExists($userId)
    {
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * 
                FROM STUDENT 
                WHERE 
                    userid=:userid
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Step 4 - Retrieve Query Results (if any)
        $existOK=FALSE;
        if($row = $stmt->fetch()){
            $existOK=TRUE;
        }
        return $existOK;
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;
    }

    public function isUserIdValid($userId)
    {
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * 
                FROM STUDENT 
                WHERE 
                    userid=:userid
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Step 4 - Retrieve Query Results (if any)
        $existOK=TRUE;
        if($row = $stmt->fetch()){
            $existOK=FALSE;
        }
        return $existOK;
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;
    }

    public function retrieveStudentByUserId($userId)
    {
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * 
                FROM STUDENT 
                WHERE 
                    userid=:userid
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $row=$stmt->fetch();
        return new Student($row['userid'], $row['password'], $row['name'], $row['school'], $row['edollar']);
    }
}
?>