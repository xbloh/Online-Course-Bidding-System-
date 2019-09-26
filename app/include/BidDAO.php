<?php
require_once 'common.php';
/**
 * 
 */
class BidDAO
{
	
	function placeBid($bid)
	{
		//put bid into database!
		$student = $_SESSION['student'];
		$userId = $student->getUserId();
		$section = $bid->getSection();
		$course = $section->getCourse();
		$courseId = $course->getCourseId();
		$sectionId = $section->getSectionId();
		$amount = $bid->getAmount();

		$sql = 'INSERT into bid values (:userId, :amount, :courseId, :sectionId)';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':amount',$amount,PDO::PARAM_STR);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
	}
	public function add($bid){

        $sql = 'INSERT INTO BID (userid, amount, code, section)
                VALUES (:userid, :amount, :code, :section)
                ';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql); 
        $userId = $bid->getUserid();
        $amount = $bid->getAmount();
        $code = $bid->getCode();
        $section = $bid->getSection();

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    public function removeAll() {
        $sql = 'ALTER TABLE BID DROP FOREIGN KEY BID_FK1;
        ALTER TABLE BID DROP FOREIGN KEY BID_FK2;
        TRUNCATE TABLE BID;
        ALTER TABLE BID ADD CONSTRAINT BID_FK1 foreign key(userid) references STUDENT(userid);
        ALTER TABLE BID ADD CONSTRAINT BID_FK2 foreign key(code,section) references SECTION(courseID,sectionID);';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  
}

?>