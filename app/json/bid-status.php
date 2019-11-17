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

	$roundDAO = new RoundDAO();
	$round = $roundDAO->retrieveCurrentRound();
	$status = $roundDAO->retrieveRoundStatus();
	$studentDAO = new StudentDAO();
	$bidDAO = new BidDAO();

	$round1Bids = $bidDAO->retrieveAllByCourseSection($course, $section, 1);
	$round2Bids = $bidDAO->retrieveAllByCourseSection($course, $section, 2);
	$allBids = array_merge($round1Bids, $round2Bids);

	if ($round == 2 && $status == 'active') {
		$bids = $round2Bids;
	} else {
		$bids = $allBids;
	}

	$size = $sectionDAO->retrieveSectionSize($course, $section);
	$vacancy = $size;

	if ($round == 1) {
		foreach ($round1Bids as $bid) {
			if (!isset($minPrice) || ($bid[4] == 'in' && $bid[3] < $minPrice)) {
				$minPrice = $bid[3];
			}
		}
	}

	if ($round == 2) {
		if (count($round2Bids) == 0 || count($round2Bids) < $size) {
			$minPrice = 10;
		} else {
			foreach ($round2Bids as $bid) {
				if (!isset($minPrice) || ($bid[4] == 'in' && $bid[3] < $minPrice)) {
					$minPrice = $bid[3];
				}
			}
			if ($status == 'active') {
				$minPrice ++;
			}
		}
	}

	if (!isset($minPrice)) {
		$minPrice = 10;
	}

	$out = ["status" => "success", "vacancy" => $vacancy, "min-bid-amount" => $minPrice, "students" => []];

	foreach ($bids as $bid) {
		$student = $studentDAO->retrieveStudentByUserId($bid[0]);
		$balance = $student->getEdollar();
		if ($bid[4] == 'in') {
			$status = "success";
		} elseif ($bid[4] == 'out') {
			$status = "fail";
		} else {
			$status = "pending";
		}

		$student = ["userid" => $bid[0], "amount" => $bid[3], "balance" => $balance, "status" => $status];
		$out["students"][] = $student;


	}
	header('Content-Type: application/json');
    echo json_encode($out, JSON_PRETTY_PRINT);
    exit();


?>