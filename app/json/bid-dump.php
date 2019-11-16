<?php

	include '../include/common.php';
	include '../include/json-protect.php';

	$bidDAO = new BidDAO();
	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$roundDAO = new RoundDAO();
	$round = $roundDAO->retrieveCurrentRound();
	$status = $roundDAO->retrieveRoundStatus();

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

	$bids = $bidDAO->bidDump($course, $section, $round);
	if ($round == 2 && $status == 'active') {
		$newbids = [];
		foreach ($bids as $bid) {
			$new = [];
			foreach ($bid as $key => $value) {
				if ($key == 'result') {
					$new[$key] = '-';
				} else {
					$new[$key] = $value;
				}


				
			}
			$newbids[] = $new;
		}

		$out = ["status" => "success", "bids" => $newbids];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);
		exit;
	} else {
		$out = ["status" => "success", "bids" => $bids];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);
	}

?>