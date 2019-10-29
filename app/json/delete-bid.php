<?php

	require_once '../include/common.php';
    include '../include/json-protect.php';

    $bidDAO = new BidDAO();
    $StudentDAO = new StudentDAO();
    $SectionDAO = new SectionDAO;
    $CourseDAO = new CourseDAO;
    $errors = [];
    $message = [];
    $totalAmtCart = 0;
    $checkMissing = ['userid', 'course', 'section'];

    $roundDAO = new RoundDAO();
    $currentRnd = $roundDAO->retrieveCurrentRound();
    $rndStatus = $roundDAO->retrieveRoundStatus();

    if(isset($_REQUEST['r']))
    {   
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
        if(isset($bid_array['userid'])&& isset($bid_array['course']) && isset($bid_array['section']))
        {
        $userId = $bid_array['userid'];
        $courseId = $bid_array['course'];
        $sectionId = $bid_array['section'];
        
        $StudentObj=$StudentDAO->retrieveStudentByUserId($userId);
        if($StudentObj==NULL)
        {
            $errors[]='invalid userid';
        }

        $bidExist=$bidDAO->isUSerCourseSectionExists($userId, $courseId, $sectionId);
        if(!$bidExist)
        {
            $errors[]='no such bid';
        }

        if (!$CourseDAO->isCourseIdExists($courseId)) 
        {
			$errors[] = "invalid course";
        }
        
		if (!$SectionDAO->isSectionIdExists($courseId, $sectionId)) {
			$errors[] = "invalid section";
        }
        if($rndStatus == 'completed')
        {
            $errors = ['round ended'];
        }
        }
        else
        {
        foreach($bid_array as $key=>$value)
        {
            if($value=='')
            {
                array_push($errors,("blank " . $key));
            }
            if(in_array($key, $checkMissing))
            {
                $checkMissing = array_diff($checkMissing, array($key));
            }
        }

        if($checkMissing!=[])
        {
            foreach($checkMissing as $missing)
            {
                array_push($errors,("missing " . $missing));
            }
        }

        }
        if (!empty($errors))
        {	
        // // var_dump($errors);
        // for ($i=0; $i < count($errors); $i++)
        // {
            $sortclass = new sort();
            $errors = $sortclass->sort_it($errors,"bootstrap");
            $result = [ 
                "status" => "error",
                "message" => $errors
            ];
        }
        else
        {	
            $biddedAmt=$bidDAO->retrieveBiddedAmt($userId, $courseId, $sectionId);
            $bidDAO->deleteBid($userId, $courseId, $sectionId);
            $StudentDAO->addEdollar($userId, $biddedAmt);
    
            if($currentRnd == '2' && $rndStatus == 'active')
            {
                $vacancy = $SectionDAO->retrieveSectionSize($courseId, $sectionId);
                $winList = $bidDAO->winBids($courseId, $sectionId, $vacancy, 2);
                $allRoundTwo = $bidDAO->retrieveAllByCourseSection($courseId, $sectionId, 2);
                for ($index=0; $index < count($allRoundTwo); $index++) 
                {
                    if(!in_array($index, $winList))
                    {
                        $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'out');
                    } 
                    else
                    {
                        $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'in');
                    }
                }
            }
            $result = [ 
                "status" => "success",
            ];
        }
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
