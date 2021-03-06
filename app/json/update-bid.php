<?php

	require_once '../include/common.php';
    include '../include/json-protect.php';

    $bidDAO = new BidDAO();
    $StudentDAO = new StudentDAO();
    $SectionDAO = new SectionDAO;
    $CourseDAO = new CourseDAO;
    $errors = [];
    $isAllowed = [];
    $message = [];
    $totalAmtCart = 0;
    $checkMissing = ['userid', 'amount', 'course', 'section'];

    $roundDAO = new RoundDAO();
    $currentRnd = $roundDAO->retrieveCurrentRound();
    $rndStatus = $roundDAO->retrieveRoundStatus();
    

    if(isset($_REQUEST['r'])){
    $php_response = json_decode($_REQUEST['r']);
    $bid_array = get_object_vars($php_response);
    $count=0;
    foreach($bid_array as $key=>$value)
    {
        if(!in_array($key, $checkMissing))
            {
                $count++;
            }
    }
    if($count!=count($checkMissing) || count($bid_array)>=count($checkMissing))
    {
        foreach($bid_array as $key=>$value)
        {
            if(!in_array($key, $checkMissing))
            {
                array_push($errors,("unknown " . $key));
            }
        }
    }
    if(isset($bid_array['userid']) && isset($bid_array['amount']) && isset($bid_array['course']) && isset($bid_array['section']) && $bid_array['userid'] != '' && $bid_array['amount'] != '' && $bid_array['course'] != '' && $bid_array['section'] != '')
    {
        $userId = $bid_array['userid'];
        $bidAmt = $bid_array['amount'];
        $courseId = $bid_array['course'];
        $sectionId = $bid_array['section'];

        // if (isset($_SESSION['bids'])) {
        $_SESSION['bids'][] = new Bid($userId, $bidAmt, $courseId, $sectionId, False, False, True);
        // } else {
            // $_SESSION['bids'] = [new Bid($userId, $bidAmt, $courseId, $sectionId, False, False, True)];
        // }
        $totalAmtCart+=$bidAmt;

        if ($bidAmt<10||!(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $bidAmt))||$bidAmt>999) {
			// if($this->amount<10||$this->amount!=number_format($this->amount,2,'.','')||$this->amount>999){
				$errors[] = "invalid amount";
        }
        
        if (!$CourseDAO->isCourseIdExists($courseId)) 
        {
			$errors[] = "invalid course";
        }

        if (!$SectionDAO->isSectionIdExists($courseId, $sectionId)) {
			$errors[] = "invalid section";
        }
        
        $StudentObj=$StudentDAO->retrieveStudentByUserId($userId);
        if($StudentObj==NULL)
        {
            $errors[]='invalid userid';
        }

        if($rndStatus == 'completed')
        {
            $errors = ['round ended'];
        }
        
        if($errors==[] && $bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checkall', $currentRnd)){
            $StudentAmt=$StudentObj->getEdollar();
            $biddedAmt=$bidDAO->retrieveBiddedAmt($userId, $courseId, $sectionId);
            $totalAmt=$StudentAmt+$biddedAmt;
            if($totalAmt-$totalAmtCart<0){
                $exceedAmt=$totalAmt-$totalAmtCart;
                $isAllowed[]="insufficient e$";
            }

            // if($currentRnd == '2' && $rndStatus == 'active')
            // {   
            //     if(!$bidDAO->checkRoundTwo($userId, $courseId, $sectionId))
            //     {
            //         $isAllowed[] = "cannot change enrolled bid";
            //     }
            // }

            if($currentRnd == '2' && $rndStatus == 'active')
            {
                $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
                $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
                $minBidAmt = $bidDAO->minBid($courseId, $sectionId, $vacancy, 2, $winList);
                if($totalAmtCart<$minBidAmt && count($winList)==$vacancy)
                {
                    $isAllowed[]="bid too low";
                }
            }
        }

        elseif($errors==[] && $bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checktillcourse', $currentRnd)){
            foreach ($_SESSION['bids'] as $bid) {
                $StudentAmt=$StudentObj->getEdollar();
                $biddedAmt=$bidDAO->totalAmountByID($userId);
                $totalAmt=$StudentAmt+$biddedAmt;
                if($totalAmt-$totalAmtCart<0)
                {
                    $exceedAmt=$totalAmt-$totalAmtCart;
                    $isAllowed[]="insufficient e$";
                }

                // if($currentRnd == '2' && $rndStatus == 'active')
                // {   
                //     if(!$bidDAO->checkRoundTwo($userId, $courseId, $sectionId))
                //     {
                //         $isAllowed[] = "cannot change enrolled bid";
                //     }
                // }

                $courseId=$bid->getCode();
                $sectionId=$bid->getSection();
                $currentBidDayTime = $SectionDAO->retrieveSectionDayTime($courseId,$sectionId);
                $currentBidDate=$currentBidDayTime[0];
                $currentBidStart=$currentBidDayTime[1];
                $currentBidEnd=$currentBidDayTime[2];
        
                $bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($userId);
                foreach ($bidded_modules as $bidded_module)
                {
                    if ($bidded_module[2] == 'out') {
                        continue;
                    }
                    if($bidded_module[0] != $courseId && $bidded_module[1] != $sectionId)
                    {
                        $moduleClassDateTime=$SectionDAO->retrieveSectionDayTime($bidded_module[0],$bidded_module[1]);
                        if($currentBidDate==$moduleClassDateTime[0]){
                            if($moduleClassDateTime[1]<=$currentBidEnd && $moduleClassDateTime[2]>=$currentBidStart){
                                $isAllowed[] = "class timetable clash";
                            }
                        }
                    }  
                }

                $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
                if($vacancy==0)
                {
                    $isAllowed[] = "no vacancy";
                }
                else
                {
                    if($currentRnd == '2' && $rndStatus == 'active')
                    {
                        $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
                        $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
                        $minBidAmt = $bidDAO->minBid($courseId, $sectionId, $vacancy, 2, $winList);
                        if($totalAmtCart<$minBidAmt && count($winList)==$vacancy)
                        {
                            $isAllowed[]="bid too low";
                        }
                    }
                }
            }
        }   
        elseif($errors==[] && !$bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checkall', $currentRnd))
        {
        foreach ($_SESSION['bids'] as $bid) {
            $isAllowed = $bid->validate();
            $noOfSectionBidded=$bidDAO->numberOfSectionsByID($userId);
            if($noOfSectionBidded+count($_SESSION['bids'])>5){
                $exceedbids=$noOfSectionBidded+count($_SESSION['bids'])-5;
                $isAllowed[]="section limit reached";
            }

            $StudentAmt=$StudentObj->getEdollar();
            if($StudentAmt-$totalAmtCart<0){
                $exceedAmt=$StudentAmt-$totalAmtCart;
                $isAllowed[]="insufficient e$";
            }
        }   

        $courseId=$bid->getCode();
        $sectionId=$bid->getSection();
        $currentBidDayTime = $SectionDAO->retrieveSectionDayTime($courseId,$sectionId);
        $currentBidDate=$currentBidDayTime[0];
        $currentBidStart=$currentBidDayTime[1];
        $currentBidEnd=$currentBidDayTime[2];

        $bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($userId);
        foreach ($bidded_modules as $bidded_module)
        {
            if ($bidded_module[2] == 'out') {
                        continue;
            }
            $moduleClassDateTime=$SectionDAO->retrieveSectionDayTime($bidded_module[0],$bidded_module[1]);
            if($currentBidDate==$moduleClassDateTime[0]){
                if($moduleClassDateTime[1]<$currentBidEnd && $moduleClassDateTime[2]>$currentBidStart){
                    $isAllowed[] = "class timetable clash";
                }
            }
        }

        $courseId=$bid->getCode();
        $sectionId=$bid->getSection();
        $currentBidDayTime = $CourseDAO->retrieveExamDateTime($courseId);
        $currentBidDate=$currentBidDayTime[0];
        $currentBidStart=$currentBidDayTime[1];
        $currentBidEnd=$currentBidDayTime[2];

        $bidded_modules=$bidDAO->retrieveCourseIdSectionIdBidded($userId);
        foreach ($bidded_modules as $bidded_module){
            if ($bidded_module[2] == 'out') {
                continue;
            }
			$moduleExamDateTime=$CourseDAO->retrieveExamDateTime($bidded_module[0]);
			if($currentBidDate==$moduleExamDateTime[0]){
				if($moduleExamDateTime[1]<$currentBidEnd && $moduleExamDateTime[2]>$currentBidStart){
					$isAllowed[] = "exam timetable clash";
				}
			}
		}
            
        $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
        if($vacancy==0)
        {
            $isAllowed[] = "no vacancy";
        }
        else
        {
            if($currentRnd == '2' && $rndStatus == 'active')
            {
                $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
                $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
                $minBidAmt = $bidDAO->minBid($courseId, $sectionId, $vacancy, 2, $winList);
                if($totalAmtCart<$minBidAmt && count($winList)==$vacancy)
                {
                    $isAllowed[]="bid too low";
                }
            }
        }
        if($currentRnd == '2' && $rndStatus == 'active')
        {
            if($bidDAO->enrolled($userId, $courseId, $sectionId))
            {   
                $isAllowed[]="course enrolled";
            }
        }

        $schoolStudent = $StudentDAO->retrieveStudentByUserId($userId)->getSchool();
        $schoolCourse = $CourseDAO->retrieveCourseById($courseId)->getSchool();
        if($currentRnd == '1' && $rndStatus == 'active')
        {
            if($schoolStudent != $schoolCourse)
            {
                $isAllowed[]="not own school course";
            }
        }
    }
    $sortclass = new sort();
    $isAllowed = $sortclass->sort_it($isAllowed,"alphabet");
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
        $sortclass = new sort();
        $errors = $sortclass->sort_it($errors,"bootstrap");
        
    }

    if (!empty($isAllowed)) {
        foreach($isAllowed as $errorCode){
            if(!in_array($errorCode, $errors)){
                array_push($errors, $errorCode);
            }
        }
    }

    if (!empty($errors))
    {	
        // $sortclass = new sort();
        // $errors = $sortclass->sort_it($errors,"bootstrap");
        $result = [ 
            "status" => "error",
            "message" => $errors
        ];
    }
    else
    {	
        foreach ($_SESSION['bids'] as $bid) {
            $userId = $bid->getUserid();
            $newAmt = $bid->getAmount();
            $courseId = $bid->getCode();
            $sectionId = $bid->getSection();
            if($bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checkall', $currentRnd)){
                $biddedAmt=$bidDAO->retrieveBiddedAmt($userId, $courseId, $sectionId);
                $bidDAO->updateBidjson($userId, $courseId, $sectionId, $newAmt, 'edollar');
                $StudentDAO->addEdollar($userId, $biddedAmt);
                $StudentDAO->deductEdollar($userId, $newAmt);
            }
            elseif($bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checktillcourse', $currentRnd)){
                $currentBidAmt=$bidDAO->retrieveBiddedAmtNoSection($userId, $courseId);
                $bidDAO->updateBidjson($userId, $courseId, $sectionId, $newAmt, 'sectionedollar');
                $StudentDAO->addEdollar($userId, $currentBidAmt);
                $StudentDAO->deductEdollar($userId, $newAmt);
            }
            elseif(!$bidDAO->checkVariableExists($userId, $courseId, $sectionId, $checktype='checkall', $currentRnd)){
                $bidDAO->add($bid);
                $StudentDAO->deductEdollar($userId, $newAmt);
            }
            if($currentRnd == '2' && $rndStatus == 'active')
            {
                $vacancy = $SectionDAO->retrieveSectionSize($courseId,$sectionId);
                $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
                // var_dump($winList);
                $allRoundTwo = $bidDAO->retrieveAllByCourseSection($courseId, $sectionId, 2);
                for ($index=0; $index < count($allRoundTwo); $index++) 
                {
                    if(!in_array($index, $winList))
                    {
                        $bidDAO->update2Status($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'out');
                    } 
                    else
                    {
                        $bidDAO->update2Status($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'in');
                    }
                }
            }
        }
        $result = [ 
            "status" => "success",
        ];
    }
    unset($_SESSION['bids']);
    }
    else
    {
        $result = [ 
            "status" => "error",
            "message" => "missing all r variables"
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

?>