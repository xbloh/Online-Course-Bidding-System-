<?php
require_once 'common.php';
/**
 * 
 */
class BidDAO
{
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
        $sectionId = $bid->getSection();
        if (!is_string($sectionId)) {
            $sectionId = $sectionId->getSectionId();
        }

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sectionId, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }


    public function removeAll() {
        $sql = 'TRUNCATE TABLE BID;';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  

    public function retrieveCourseIdSectionIdBidded($userid)
    {
        $sql = 'SELECT * from bid where userid=:userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [$row['code'], $row['section']];
        }

        return $result;
    }

    public function numberOfSectionsByID($userId){


        $sql = 'SELECT COUNT(userid) FROM BID WHERE userid=:userid
                ';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        // $result = $stmt->execute();
        // return $result;
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['COUNT(userid)'];
    }

    public function totalAmountByID($userId){

    $sql='SELECT sum(amount) FROM BID WHERE userid=:userid
            ';
    $connMgr = new ConnectionManager();       
    $conn = $connMgr->getConnection();
    
    $stmt = $conn->prepare($sql); 

    $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    // $result = $stmt->execute();
    // return $result;
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['sum(amount)'];
    }


}

?>