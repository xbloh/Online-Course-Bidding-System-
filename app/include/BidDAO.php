<?php
require_once 'common.php';
/**
 * 
 */
class BidDAO
{
	public function add($bid){

        $roundDAO = new RoundDAO();
        $current = $roundDAO->retrieveCurrentRound();

        $sql = 'INSERT INTO BID (userid, amount, code, section, result, round)
                VALUES (:userid, :amount, :code, :section, "-", :current)
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
        $stmt->bindParam(':current', $current, PDO::PARAM_INT);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }


    public function removeAll() {
        // $sql = 'TRUNCATE TABLE BID';
        $sql = 'delete from BID';

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
            $result[] = [$row['code'], $row['section'], $row['result'], $row['round']];
        }

        return $result;
    }

    public function retrieveBiddedAmt($userid, $code, $section)
    {
        $sql = 'SELECT amount FROM bid WHERE userid=:userid AND code=:code AND section=:section';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->bindParam(':code',$code,PDO::PARAM_STR);
        $stmt->bindParam(':section',$section,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['amount'];
        }

        return $result[0];
    }

    public function retrieveBiddedAmtNoSection($userid, $code)
    {
        $sql = 'SELECT amount FROM bid WHERE userid=:userid AND code=:code';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid',$userid,PDO::PARAM_STR);
        $stmt->bindParam(':code',$code,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if($row = $stmt->fetch()){
            return $row['amount'];
        }
    }


    public function numberOfSectionsByID($userId){


        $sql = 'SELECT COUNT(userid) as userCount FROM BID WHERE userid=:userid
                ';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        // $result = $stmt->execute();
        // return $result;
        $stmt->execute();

        if($row = $stmt->fetch()){
            return $row['userCount'];
        }
    }

    public function totalAmountByID($userId){

    $sql='SELECT sum(amount) as ttlAmt FROM BID WHERE userid=:userid';
    $connMgr = new ConnectionManager();       
    $conn = $connMgr->getConnection();
    
    $stmt = $conn->prepare($sql); 

    $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    // $result = $stmt->execute();
    // return $result;

    $stmt->execute();
    // var_dump($row = $stmt->fetch());
        
    if($row = $stmt->fetch()){
        if($row['ttlAmt']==NULL){
            return 0;
        }
        else{
            return $row['ttlAmt'];
        }
    }
    
    }

    public function deleteBid($userId, $courseId, $sectionId) {
        $sql='DELETE FROM BID WHERE userid=:userid AND code=:code AND section=:section';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':code', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sectionId, PDO::PARAM_STR);
        // Add code to delete a record from employmentstat table in database, 
        // given the id of an existing record
        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $status=False;
        // $status=$stmt->execute();
        // echo"$userId, $courseId,$sectionId";
        // if(!$status){
        if($stmt->execute()){
            $status=True;
        }
        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function updateBid($userId, $courseId, $sectionId, $newAmt) {
        $sql='UPDATE BID SET AMOUNT=:newamount WHERE userid=:userid AND code=:code AND section=:section';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':code', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sectionId, PDO::PARAM_STR);
        $stmt->bindParam(':newamount', $newAmt, PDO::PARAM_STR);
    
        $status=FALSE;
        
        if($stmt->execute()){
            $status=TRUE;
        }
        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function isUSerCourseSectionExists($userId, $courseId, $sectionId)
    {
        $sql = 'SELECT count(*) as countUserSectionCourse from bid where code = :courseId and userid = :userId and section = :sectionId';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $row = $stmt->fetch();
        // var_dump($row['countnum']);
        $existOK=FALSE;
        // if($row=$stmt->fetch()){
        if($row['countUserSectionCourse']>0){
            $existOK=TRUE;
        }
        return $existOK;

        // Step 4 - Retrieve Query Results (if any)
        // return $row=$stmt->fetch();
        $stmt = null;
        $pdo = null;
    }


    public function bidsByCourseSection($courseId, $sectionId)
    {
        $sql = 'SELECT userid,amount from bid where code=:courseId and section=:sectionId ORDER BY AMOUNT DESC';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [$row['userid'], $row['amount']];
        }

        return $result;
    }

    public function checkVariableExists($userId, $courseId, $sectionId, $checktype, $round)
    {
        if($checktype=='checkall')
        {
            $sql = 'SELECT count(*) as countUserSectionCourse from bid where code = :courseId and userid = :userId and section = :sectionId and round = :round';
        }
        elseif($checktype=='checktillcourse')
        {
            $sql = 'SELECT count(*) as countUserSectionCourse from bid where code = :courseId and userid = :userId and round = :round';
        }
        

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':round',$round,PDO::PARAM_STR);
        if($checktype=='checkall')
        {
            $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        }
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $row = $stmt->fetch();
        // var_dump($row);
        $existOK=FALSE;
        // if($row=$stmt->fetch()){
        if($row['countUserSectionCourse']>0){
            $existOK=TRUE;
        }
        return $existOK;

        // Step 4 - Retrieve Query Results (if any)
        // return $row=$stmt->fetch();
        $stmt = null;
        $pdo = null;
    }

    public function updateBidjson($userId, $courseId, $sectionId, $newAmt, $updatetype) 
    {
        if($updatetype=='edollar')
        {
            $sql='UPDATE bid SET amount=:newamount WHERE userid=:userid AND code=:code AND section=:section';
        }
        elseif($updatetype=='sectionedollar')
        {
            $sql='UPDATE bid SET amount=:newamount ,section=:section WHERE userid=:userid AND code=:code';
        }

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':code', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':section', $sectionId, PDO::PARAM_STR);
        $stmt->bindParam(':newamount', $newAmt, PDO::PARAM_STR);
    
        $status=FALSE;
        
        if($stmt->execute()){
            $status=TRUE;
        }
        $stmt = null;
        $pdo = null;
        return $status;
    }

    public function dump()
    {
        $sql = 'SELECT * from bid order by code, section, amount desc, userid';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['userid' => $row['userid'], 'amount' => $row['amount'], 'course' => $row['code'], 'section' => $row['section']];
        }
        return $result;
    }

    public function ssDump()
    {
        $sql = 'SELECT * from bid order by code, section, amount desc, userid and result = "in"';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['userid' => $row['userid'], 'course' => $row['code'], 'section' => $row['section'], 'amount' => $row['amount']];
        }
        return $result;
    }

    public function bidDump($courseId, $sectionId, $round)
    {
        $sql = 'SELECT * from bid where code = :courseId and section = :sectionId and round = :round order by amount desc, userid';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_STR);
        $stmt->bindParam(':round', $round, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        $i = 1;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['row' => $i, 'userid' => $row['userid'], 'amount' => $row['amount'], 'result' => $row['result']];
            $i ++;
        }
        return $result;
    }

    public function updateStatus($userid, $code, $section, $bidStatus) {
        $sql= 'UPDATE BID SET RESULT=:bidStatus WHERE userid=:userid AND code=:code AND section=:section';

        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':bidStatus', $bidStatus, PDO::PARAM_STR);
    
        $status=FALSE;
        
        if($stmt->execute()){
            $status=TRUE;
        }
        $stmt = null;
        $pdo = null;
        return $status;
    }
        
    public function retrieveSuccessfulBids($courseId, $sectionId)
    {
        $sql = 'SELECT * from bid where code = :courseId and section = :sectionId and result = "in" order by userid';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_STR);
        $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['userid' => $row['userid'], 'amount' => $row['amount']];
        }
        return $result;
    }

    public function retrieveUserSuccessfulBids($userId)
    {
        $sql = 'SELECT * from bid where userid = :userId and result = "in" order by userid';

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ['code' => $row['code'], 'section' => $row['section']];
        }
        return $result;
    }

    public function enrolled($userId, $courseId, $sectionId)
    {
        $sql = 'SELECT count(*) as countUserSectionCourse from bid where code = :courseId and userid = :userId and section = :sectionId and result = "in"';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $row = $stmt->fetch();
        // var_dump($row['countnum']);
        $existOK=FALSE;
        // if($row=$stmt->fetch()){
        if($row['countUserSectionCourse']>0){
            $existOK=TRUE;
        }
        return $existOK;

        // Step 4 - Retrieve Query Results (if any)
        // return $row=$stmt->fetch();
        $stmt = null;
        $pdo = null;
    }

    public function minBid($courseId, $sectionId, $vacancy, $round, $winList)
    {
        $sql = 'SELECT * from bid where code = :courseId and section = :sectionId and round = :round order by amount desc';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->bindParam(':round',$round,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['amount'];
        }

        // if(count($result)<$vacancy)
        // {
        //     return 10;
        // }
        // else
        // {
        //     return $result[$vacancy-1]+1;
        // }
        if(count($winList)<$vacancy)
        {
            return 10.00;
        }
        else
        {
            $minBidIndex = $winList[$vacancy-1];
            return $result[$minBidIndex]+1;
        }
        
    }

    public function winBids($courseId, $sectionId, $vacancy, $round)
    {
        $sql = 'SELECT count(*) as countAmount, amount from bid where code = :courseId and section = :sectionId and round = :round group by amount order by amount desc';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->bindParam(':round',$round,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [$row['countAmount'],$row['amount']];
        }

        $inList=[];
        $index=0;
        foreach($result as $amountVar)
        {
            if(count($inList)+$amountVar[0]<=$vacancy)
            {
                for ($i=0; $i < $amountVar[0]; $i++)
                { 
                    $inList[] = $index;
                    $index++;
                }
            }
            else
            {
                $index+=$amountVar[0];
            }
        }
        // var_dump($inList);
        return $inList;
        // if(count($inList)<$vacancy)
        // {
        //     return 10;
        // }
        // else
        // {
        //     $minBidIndex = $inList[$vacancy-1];
        //     return $result[$minBidIndex];
        // }
    }

    public function retrieveAllByCourseSection($courseId, $sectionId, $round)
    {
        $sql = 'SELECT * from bid where code = :courseId and section = :sectionId and round = :round order by amount desc';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->bindParam(':round',$round,PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [$row['userid'], $row['code'], $row['section'], $row['amount'], $row['result']];
        }

        return $result;
    }

    public function checkRoundTwo($userId, $courseId, $sectionId)
    {
        $sql = 'SELECT * from bid where userid = :userid and code = :courseId and section = :sectionId';

        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->bindParam(':userid',$userId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = $row['round'];
        $correct = FALSE;
        if($result=='2')
        {
            $correct = TRUE;
        }
        return $correct;
        
    }

    public function bidExists($userId, $courseId, $sectionId)
    {
        $sql = 'SELECT * from bid where userid = :userId and code = :courseId and section = :sectionId and result = "-"';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseId',$courseId,PDO::PARAM_STR);
        $stmt->bindParam(':sectionId',$sectionId,PDO::PARAM_STR);
        $stmt->bindParam(':userId',$userId,PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        $count = 0;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count ++;
        }

        return $count > 0;
    }

}

?>