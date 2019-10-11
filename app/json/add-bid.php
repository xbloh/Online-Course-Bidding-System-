<?php

	require_once '../include/common.php';
    require_once '../include/token.php';

    $bidDAO = new BidDAO();
    $StudentDAO = new StudentDAO();
    $SectionDAO = new SectionDAO;
    $CourseDAO = new CourseDAO;
    $errors = [];
    $message = [];
    $totalAmtCart = 0;
    $checkMissing = ['userid', 'amount', 'course', 'section'];
    

    if(isset($_REQUEST['r'])){
    $php_response = json_decode($_REQUEST['r']);
    $bid_array = get_object_vars($php_response);
    if($bid_array['userid']!='' && $bid_array['amount']!='' && $bid_array['course']!='' && $bid_array['section']!='')
    {
        $userId = $bid_array['userid'];
        $bidAmt = $bid_array['amount'];
        $courseId = $bid_array['course'];
        $sectionId = $bid_array['section'];

        if (isset($_SESSION['bids'])) {
			$_SESSION['bids'][] = new Bid($userId, $bidAmt, $courseId, $sectionId, False, True);
		} else {
			$_SESSION['bids'] = [new Bid($userId, $bidAmt, $courseId, $sectionId, False, True)];
        }
        $totalAmtCart+=$bidAmt;

        // var_dump($_SESSION['bids']);
        foreach ($_SESSION['bids'] as $bid) {
            $StudentObj=$StudentDAO->retrieveStudentByUserId($userId);
            // var_dump($StudentObj);
            if($StudentObj==NULL){
                $errors[]='Invalid User';
            }
            else{
            $isAllowed = $bid->validate();
            $noOfSectionBidded=$bidDAO->numberOfSectionsByID($userId);
            if($noOfSectionBidded+count($_SESSION['bids'])>5){
                $exceedbids=$noOfSectionBidded+count($_SESSION['bids'])-5;
                $isAllowed[]="Exceeded Section Limit by ".$exceedbids.".(Max 5)";
            }
            $StudentAmt=$StudentObj->getEdollar();
            $biddedAmt=$bidDAO->totalAmountByID($userId);
            $totalAmtBid=$biddedAmt+$totalAmtCart;
            if($StudentAmt-$totalAmtBid<0){
                $exceedAmt=$StudentAmt-$totalAmtBid;
                $isAllowed[]="Exceeded E-dollar Amount by ".abs($exceedAmt).".(Student E-dollar: ".$StudentAmt.")";
            }
            
            $courseId=$bid->getCode();
            $sectionId=$bid->getSection();
            $currentBidDayTime = $SectionDAO->retrieveSectionDayTime($courseId,$sectionId);
            $currentBidDate=$currentBidDayTime[0];
            $currentBidStart=$currentBidDayTime[1];
            $currentBidEnd=$currentBidDayTime[2];
            foreach ($_SESSION['bids'] as $bid2) {
                if($bid2!=$bid){
                $courseId2=$bid2->getCode();
                $sectionId2=$bid2->getSection();
                $moduleClassDateTime=$SectionDAO->retrieveSectionDayTime($courseId2,$sectionId2);
                    if($currentBidDate==$moduleClassDateTime[0]){
                        if($moduleClassDateTime[1]<=$currentBidStart||$moduleClassDateTime[2]<=$currentBidEnd){
                        $isAllowed[] = "Class timetable clash  ".$courseId."  ".$sectionId;
                    }
                }
            }
            }
        
            $courseId=$bid->getCode();
            $sectionId=$bid->getSection();
            $currentBidDayTime2 = $CourseDAO->retrieveExamDateTime($courseId);
            $currentBidDate2=$currentBidDayTime2[0];
            $currentBidStart2=$currentBidDayTime2[1];
            $currentBidEnd2=$currentBidDayTime2[2];
            foreach ($_SESSION['bids'] as $bid2) {
                if($bid2!=$bid){
                $courseId2=$bid2->getCode();
                $moduleExamDateTime=$CourseDAO->retrieveExamDateTime($courseId2);
                if($currentBidDate2==$moduleExamDateTime[0]){
                    if($moduleExamDateTime[1]<=$currentBidStart2||$moduleExamDateTime[2]>=$currentBidEnd2){
                        $isAllowed[] = "Exam timetable clash  ".$courseId."  ".$sectionId;
                    }
                }
            }
            }
        
        
        
            // var_dump(count($_SESSION['bids']));
            // var_dump($isAllowed);
            if (!empty($isAllowed)) {
                // var_dump($isAllowed);
                foreach($isAllowed as $errorCode){
                    // echo $errorCode;
                    if(!in_array($errorCode, $errors)){
                        array_push($errors, $errorCode);
                    }
                }
            }
        }
        }

    }
    else
    {
        foreach($bid_array as $key=>$value){
            if($value==''){
                array_push($errors,("blank " . $key));
            }
            if(in_array($key, $checkMissing)){
                $checkMissing = array_diff($checkMissing, array($key));
            }
        }

        if($checkMissing!=[]){
            foreach($checkMissing as $missing){
                array_push($errors,("missing " . $missing));
            }
        }

        // if (!empty($message)) {
        //     $errors[] = ["message" => $message];
        // }
        
    }

    if (!empty($errors))
    {	
        // $sortclass = new Sort();
        // $errors = $sortclass->sort_it($errors,"bootstrap");
        $result = [ 
            "status" => "error",
            "message" => $errors
        ];
    }
    else
    {	
        foreach ($_SESSION['bids'] as $bid) {
            $bidDAO->add($bid);
        }
        $result = [ 
            "status" => "success",
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
    
    unset($_SESSION['bids']);
    }

?>