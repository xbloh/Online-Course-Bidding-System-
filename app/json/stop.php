<?php

	require_once '../include/common.php';
	include '../include/json-protect.php';

	$roundDAO = new RoundDAO();
	$status = $roundDAO->retrieveRoundStatus();
	$current = $roundDAO->retrieveCurrentRound();
	$errors = [];
	$courseDAO = new CourseDAO();
	$sectiondao = new SectionDAO();
	$bid = new BidDAO();

	if ($status != "active" ) {
		$errors[] = "round already ended";
		$out = ["status" => "error", "message" => $errors];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRETTY_PRINT);
		exit();
	} else {
		if ($current == 2) {
			if ($roundDAO->endRound2()) {
				$out = ["status" => "success"];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		} elseif ($current == 1) {
			if ($roundDAO->endRound1()) {
				$failBids = [];
				$courses = $courseDAO->retrieveAllCourses();

				$clearingPrice = NULL;
				$succesfulBids = [];
				foreach($courses as $course)
				{
				    $courseId = $course->getCourseId(); 
				    $sectionIds = $sectiondao->retrieveSectionIds($courseId);
				    foreach($sectionIds as $sectionId)
				    {
				        $bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);
				        $sectionSize = $sectiondao->retrieveSectionSize($courseId,$sectionId);
				        //var_dump($bidByUserid);
				        if(count($bidByUserid) >= $sectionSize)
				        {
				            $clearingPrice = $bidByUserid[$sectionSize-1][1];
				            //echo $clearingPrice;
				            foreach($bidByUserid as $bidUser)
				            {
				                $user = $bidUser[0];
				                $amount = $bidUser[1];
				                if($amount > $clearingPrice)
				                {
				                    $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
				                } elseif ($amount == $clearingPrice) {
				                    if ($bidByUserid[$sectionSize][1] == $clearingPrice) {
				                        $failBids[] = [$user, $amount, $courseId, $sectionId];
				                    } else {
				                        $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
				                    }
				                } else {
				                    $failBids[] = [$user, $amount, $courseId, $sectionId];
				                }
				            }
				            
				        }
				        else
				        {
				            foreach($bidByUserid as $bidUser)
				            {
				                $user = $bidUser[0];
				                $amount = $bidUser[1];
				                $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
				            }
				        }
				    }
				}
				//var_dump($succesfulBids);
				//var_dump($failBids);
				foreach($succesfulBids as $successBid)
				{
				    $userid = $successBid[0];
				    $code = $successBid[2];
				    $section = $successBid[3];
				    $bidStatus = 'in';
				    $bid->updateStatus($userid, $code, $section, $bidStatus);
				}

				foreach ($failBids as $failbid) {
				    //var_dump($failbid);
				    $userid = $failbid[0];
				    $toAdd = $failbid[1];
				    $code = $failbid[2];
				    $section = $failbid[3];
				    $bidStatus = 'out';
				    $bid->updateStatus($userid, $code, $section, $bidStatus);
				    $studentDAO->addEdollar($userid, $toAdd);
				}
				$out = ["status" => "success"];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		}
	}
?>