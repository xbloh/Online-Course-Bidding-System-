<?php
	require_once '../include/common.php';
	include '../include/json-protect.php';

	$request = json_decode($_REQUEST['r']);
	$errors = [];

	if (!isset($request->course)) {
		$errors[] = "missing course";
	} elseif ($request->course == '') {
		$errors[] = "blank course";
	}

	if (!isset($request->section)) {
		$errors[] = "missing section";
	} elseif ($request->section == '') {
		$errors[] = "blank section";
	}

	if (count($errors) > 0) {
		$out = ["status" => "error", "message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

	$course = $request->course;
	$section = $request->section;
	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$errors = [];
	$roundDAO = new RoundDAO();
	$round = $roundDAO->retrieveCurrentRound();
	$status = $roundDAO->retrieveRoundStatus();

	if (!$courseDAO->isCourseIdExists($course)) {
		$errors[] = "invalid course";
	} else {
		if (!in_array($section, $sectionDAO->retrieveSectionIds($course))) {
			$errors[] = "invalid section";
		}
	}

	if (count($errors) > 0) {
		$out = ["status" => "error" , "message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

	if (!($round == 2 && $status == 'active')) {
		$bidDAO = new BidDAO();
		$students = $bidDAO->retrieveSuccessfulBids($course, $section);

		$out = ["status" => "success", "students" => $students];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);
		exit;
	} else {
	
		$bidDAO = new BidDAO();
		$students = $bidDAO->retrieveSuccessfulBids($course, $section, True);

		$out = ["status" => "success", "students" => $students];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);

	}


?>