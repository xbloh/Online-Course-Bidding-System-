<?php

require_once 'include/common.php';
$student = $_SESSION['student'];
$userId = $student->getUserId();

foreach ($_SESSION['cart'] as $section) {
	$course = $section->getCourse();
	$courseId = $course->getCourseId();


	if (isset($_POST[$identifier])) {
		$bidAmt = $_POST[$identifier] + 0;

		if (isset($_SESSION['bids'])) {
			$_SESSION['bids'][] = new Bid($userId, $bidAmt, $courseId, $section);
		} else {
			$_SESSION['bids'] = [new Bid(($userId, $bidAmt, $courseId, $section)];
		}
	}
}

$bidErrors = 0;

foreach ($_SESSION['bids'] as $bid) {
	$isAllowed = $bid->validate();
	//if (!$isAllowed) {
		//output error message and go back to placeBids.php
	//	$bidErrors++;

	//}


}

if ($bidErrors == 0) {
	$bidDAO = new BidDAO();
	foreach ($_SESSION['bids'] as $bid) {
		$bidDAO->placeBid($bid);
	}
}

?>