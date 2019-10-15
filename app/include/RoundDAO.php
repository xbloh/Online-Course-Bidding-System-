<?php

require_once 'common.php';

class RoundDAO {

    public function startRound1()
		{
			$sql = 'UPDATE rounds SET round = 1, status = "active", WHERE round = 0';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();
            $stmt = $conn->prepare($sql);
            
            $status = FALSE;
            if($stmt->execute())
            {
                $status = TRUE;
            };
            $stmt = null;
            $pdo = null;
            return $status;
        }
        
        public function endRound1()
		{
			$sql = 'UPDATE rounds SET status = "completed", WHERE round = 1';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();
            $stmt = $conn->prepare($sql);
            
            $status = FALSE;
            if($stmt->execute())
            {
                $status = TRUE;
            };
            $stmt = null;
            $pdo = null;
            return $status;
        }
        
        public function startRound2()
		{
			$sql = 'UPDATE rounds SET round = 2, status = "active", WHERE round = 1';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();
            $stmt = $conn->prepare($sql);
            
            $status = FALSE;
            if($stmt->execute())
            {
                $status = TRUE;
            };
            $stmt = null;
            $pdo = null;
            return $status;
        }

        public function endRound2()
		{
			$sql = 'UPDATE rounds SET status = "completed", WHERE round = 2';
        
	        $connMgr = new ConnectionManager();      
	        $conn = $connMgr->getConnection();
            $stmt = $conn->prepare($sql);
            
            $status = FALSE;
            if($stmt->execute())
            {
                $status = TRUE;
            };
            $stmt = null;
            $pdo = null;
            return $status;
        }

}


?>