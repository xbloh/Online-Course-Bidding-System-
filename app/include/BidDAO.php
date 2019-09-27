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
        $sql = 'TRUNCATE TABLE BID;';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }  
}

?>