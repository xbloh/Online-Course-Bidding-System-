<?php
	require_once '../include/common.php';
	
	$request = json_decode($_REQUEST['r']);
	$userId = $request->userid;
	$courseId = $request->course;
	$sectionId = $request->section;

	$studentDAO = new StudentDAO();
	$bidDAO = new BidDAO();
	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();

	$errors = [];

	if (!$courseDAO->isCourseIdExists($courseId)) {
		$errors[] = "invalid course";
	} else {
		if (!$sectionDAO->isSectionIdExists($courseId, $sectionId)) {
			$errors[] = "invalid section";
		}
	}

	if (!$studentDAO->isUserIdValid($userId)) {
		$errors[] = "invalid userid";
	}

	if ($roundDAO->retrieveRoundStatus() != "active") {
		$errors[] = "round not active";
	}

	if (count($errors) > 0) {
		$out = ["status" => "error", "message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

	if (!$bidDAO->enrolled($userId, $courseId, $sectionId)) {
		$errors[] = "no such enrollment record";
		$out = ["status" => "error", "message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	} else {
		$amount = $bidDAO->retrieveBiddedAmt($userId, $courseId, $sectionId);
		$studentDAO->addEdollar($userId, $amount);
		if (!$bidDAO->deleteBid($userId, $courseId, $sectionId)) {
			$errors[] = "some error";
		}

		$out = ["status" => "success"];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

?>