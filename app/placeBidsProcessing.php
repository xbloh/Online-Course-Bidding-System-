<?php

require_once 'include/common.php';

$bidDAO = new BidDAO();
$StudentDAO = new StudentDAO();
$SectionDAO = new SectionDAO;
$CourseDAO = new CourseDAO;
$roundDAO = new RoundDAO();
$student = $_SESSION['student'];
$userId = $student->getUserId();
$totalAmtCart=0;
$currentRnd = $roundDAO->retrieveCurrentRound();
$rndStatus = $roundDAO->retrieveRoundStatus();

foreach ($_SESSION['cart'] as $sectionSelected) {
	if($sectionSelected!=NULL){
		$courseId = $sectionSelected[0];
		$sectionId = $sectionSelected[1];
		$identifier = $courseId . $sectionId;

		if ($_POST[$identifier]<10||!(preg_match('/^(?:[0-9]{0,3})\.{0,1}\d{0,2}$/', $_POST[$identifier]))||$_POST[$identifier]>999) {
			$_SESSION['errors']=['Input should only be numbers'];
			echo "<h1>Bids cannot be placed because of:</h1>";
			printErrors();
			echo "<a href = 'welcome.php'>go back home</a>";
			unset($_SESSION['bids']);
			unset($_SESSION['cart']);
			die;
		}

		if (isset($_POST[$identifier])) {
			$bidAmt = $_POST[$identifier] + 0;

			if (isset($_SESSION['bids'])) {
				$_SESSION['bids'][] = new Bid($userId, $bidAmt, $courseId, $sectionId, False, True);
			} else {
				$_SESSION['bids'] = [new Bid($userId, $bidAmt, $courseId, $sectionId, False, True)];
			}
		$totalAmtCart+=$bidAmt;
		}
	}
}

$bidErrors = 0;


// var_dump($_SESSION['bids']);

foreach ($_SESSION['bids'] as $bid) {
	$isAllowed = $bid->validate();
	$noOfSectionBidded=$bidDAO->numberOfSectionsByID($userId);
	if($noOfSectionBidded+count($_SESSION['bids'])>5){
		$exceedbids=$noOfSectionBidded+count($_SESSION['bids'])-5;
		$isAllowed[]="Exceeded Section Limit by ".$exceedbids.".(Max 5)";
	}
	$StudentObj=$StudentDAO->retrieveStudentByUserId($userId);
	$StudentAmt=$StudentObj->getEdollar();
	if($StudentAmt-$totalAmtCart<0){
		$exceedAmt=$totalAmtCart-$StudentAmt;
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
	$errorList=[];
	if (!empty($isAllowed)) {
		// var_dump($isAllowed);
		foreach($isAllowed as $errorCode){
			// echo $errorCode;
			if(!in_array($errorCode, $errorList)){
				array_push($errorList, $errorCode);
				$bidErrors++;
			}
		}
		}//output error message and go back to placeBids.php
	

	//}


}
// var_dump($bidErrors);
// var_dump($_SESSION['errors']);
if ($bidErrors == 0) {
	$bidDAO = new BidDAO();
	foreach ($_SESSION['bids'] as $bid) {
		$bidDAO->add($bid);
		$userid = $bid->getUserid();
		$amount = $bid->getAmount();
		$StudentDAO->deductEdollar($userid, $amount);
	}
	echo "<h1>BIDS PLACED!!! GOOD LUCK</h1>
	<a href = 'welcome.php'>go back home</a>";
	unset($_SESSION['bids']);
	unset($_SESSION['cart']);
}
else{
	$_SESSION['errors']=$errorList;
	// var_dump($errorList);
	echo "<h1>Bids cannot be placed because of:</h1>";
	printErrors();
	echo "<a href = 'welcome.php'>go back home</a>";
	unset($_SESSION['bids']);
	unset($_SESSION['cart']);
}

?>