<?php

	include '../include/common.php';

	$bidDAO = new BidDAO();
	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$errors = [];

	$request = json_decode($_REQUEST['r']);

	$course = $request->course;
	$section = $request->section;

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

	$bids = $bidDAO->retrieveBySection($course, $section);
	$out = ["status" => "success", "bids" => $bids];
	header('Content-Type: application/json');
	echo json_encode($out, JSON_PRETTY_PRINT);

?>