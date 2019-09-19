<?php 
require_once 'common.php';

class StudentDAO{
    public function getStudent($userid)
    {
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        $sql = "SELECT * from student where userid=:userid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userid,PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            $student = new Student($row['userid'], $row['name'], $row['school'], $row['edollar'])
        }

        return $student;
    }

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
                $_SESSION['student'] = $this->getStudent($userid);
            }else{
                $return_message='Incorrect Password!';
            }
        }else{
            $return_message='Invalid Username!';
        }
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $return_message;
    }
}
?>