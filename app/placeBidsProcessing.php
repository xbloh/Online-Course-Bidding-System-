<?php

require_once 'include/common.php';
include 'menu.php';

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
			$_SESSION['errors']=['Invalid bid amount'];
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
	$sectionSize = $SectionDAO->retrieveSectionSize($bid->getCode(), $bid->getSection());
	if ($sectionSize == 0) {
		$isAllowed[]="Section has no more vacancies";
	}
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
				if($moduleClassDateTime[1]<=$currentBidEnd && $moduleClassDateTime[2]>=$currentBidStart){
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
			if($moduleExamDateTime[1]<=$currentBidEnd && $moduleExamDateTime[2]>=$currentBidStart){
				$isAllowed[] = "Exam timetable clash  ".$courseId."  ".$sectionId;
			}
		}
	}
	}
	if($currentRnd == '2' && $rndStatus == 'active')
	{
		$currentVacancy = $SectionDAO->retrieveSectionSize($courseId,$sectionId);
		$winList = $bidDAO->winBids($courseId, $sectionId, $currentVacancy, 2);
		$minBidAmt = $bidDAO->minBid($courseId, $sectionId, $currentVacancy, 2, $winList);
		if($bid->getAmount()<$minBidAmt && count($winList)==$currentVacancy)
		{
			$isAllowed[] = "Insufficient bidded amount";
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
		$userid = $bid->getUserid();
		$amount = $bid->getAmount();
		$courseId=$bid->getCode();
		$sectionId=$bid->getSection();
		if($currentRnd == '2' && $rndStatus == 'active')
		{
			if ($bidDAO->isUSerCourseSectionExists($userid, $courseId, $sectionId)) {
				$bidDAO->deleteBid($userid, $courseId, $sectionId);
			}
			$bidDAO->add($bid);
			$winList = $bidDAO->winBids($courseId, $sectionId, $currentVacancy, 2);
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
		else
		{
			$bidDAO->add($bid);
		}
		$StudentDAO->deductEdollar($userid, $amount);
	}
	echo "<h1>BIDS PLACED!!! GOOD LUCK</h1>";

	$eDollar = $StudentDAO->retrieveStudentByUserId($userid)->getEdollar();
	echo"<h2>Remaining e$".$eDollar."</h2><br>";
	if($currentRnd == '2' && $rndStatus == 'active')
	{
		echo "<h2>Number of vacancy : {$currentVacancy}</h2><h2>Minimum bid : {$minBidAmt}</h2>";
	}
	echo "<a href = 'welcome.php'>go back home</a>";
	unset($_SESSION['bids']);
	unset($_SESSION['cart']);
}
else{
	$_SESSION['errors']=$errorList;
	// var_dump($errorList);
	echo "<h1>Bids cannot be placed because of:</h1>";
	printErrors();
	if($currentRnd == '2' && $rndStatus == 'active')
	{
		echo "<h2>Number of vacancy : {$currentVacancy}</h2><h2>Minimum bid : {$minBidAmt}</h2>";
	}
	echo "<a href = 'welcome.php'>go back home</a>";
	unset($_SESSION['bids']);
	unset($_SESSION['cart']);
}

?>