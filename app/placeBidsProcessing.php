<?php

require_once 'include/common.php';

foreach ($_SESSION['cart'] as $section) {
	$identifier = $section->getCourseId() . $section->getSectionId();

	if (isset($_POST[$identifier])) {
		$bidAmt = $_POST[$identifier] + 0;

		if (isset($_SESSION['bids'])) {
			$_SESSION['bids'][] = new Bid($section, $bidAmt);
		} else {
			$_SESSION['bids'] = [new Bid($section, $bidAmt)];
		}
	}
}

var_dump($_SESSION['bids']);

?>