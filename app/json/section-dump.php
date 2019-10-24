<?php
	require_once '../include/common.php';
	include '../include/json-protect.php';

	$request = json_decode($_REQUEST['r']);

	$course = $request->course;
	$section = $request->section;
	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$errors = [];

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

	$bidDAO = new BidDAO();
	$students = $bidDAO->retrieveSuccessfulBids($course, $section);

	$out = ["status" => "success", "students" => $students];
	header('Content-Type: application/json');
	echo json_encode($out, JSON_PRETTY_PRINT);


?>