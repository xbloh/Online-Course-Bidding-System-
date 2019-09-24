<?php

require_once 'include/common.php';

foreach ($_SESSION['cart'] as $section) {
	$course = $section->getCourse();
	$identifier = $course->getCourseId() . $section->getSectionId();

	if (isset($_POST[$identifier])) {
		$bidAmt = $_POST[$identifier] + 0;

		if (isset($_SESSION['bids'])) {
			$_SESSION['bids'][] = new Bid($section, $bidAmt);
		} else {
			$_SESSION['bids'] = [new Bid($section, $bidAmt)];
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