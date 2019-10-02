<?php

require_once 'include/common.php';
$student = $_SESSION['student'];
$userId = $student->getUserId();

foreach ($_SESSION['cart'] as $section) {
	if($section!=NULL){
	$course = $section->getCourse();
	$courseId = $course->getCourseId();
	$identifier = $course->getCourseId() . $section->getSectionId();
	$sectionId = $section->getSectionId();

	if (isset($_POST[$identifier])) {
		$bidAmt = $_POST[$identifier] + 0;

		if (isset($_SESSION['bids'])) {
			$_SESSION['bids'][] = new Bid($userId, $bidAmt, $courseId, $sectionId);
		} else {
			$_SESSION['bids'] = [new Bid($userId, $bidAmt, $courseId, $sectionId)];
		}
	}
}
}

$bidErrors = 0;

foreach ($_SESSION['bids'] as $bid) {
	//$isAllowed = $bid->validate();
	//if (!$isAllowed) {
		//output error message and go back to placeBids.php
	//	$bidErrors++;

	//}


}

if ($bidErrors == 0) {
	$bidDAO = new BidDAO();
	foreach ($_SESSION['bids'] as $bid) {
		$bidDAO->add($bid);
	}
	echo "<h1>BIDS PLACED!!! GOOD LUCK</h1>
	<a href = 'welcome.php'>go back home</a>";
	unset($_SESSION['bids']);
	unset($_SESSION['cart']);
}

?>