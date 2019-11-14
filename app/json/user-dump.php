<?php

	include '../include/common.php';
	include '../include/json-protect.php';

	$request = json_decode($_REQUEST['r']);
	$errors = [];

	if (!isset($request->userid)) {
		$errors[] = "missing userid";
	} elseif ($request->userid == '') {
		$errors[] = "blank userid";
	}

	if (count($errors) > 0) {
		$out = ["status" => "error",
				"message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

	$studentDAO = new StudentDAO();
	// $checkMissing = ['userid'];

	$is_valid = $studentDAO->isUserIdValid($request->userid);

	if ($is_valid) {
		$student = $studentDAO->retrieveStudentByUserId($request->userid);
		$out = [
			"status" => "success",
			"userid" => $student->getUserId(),
			"password" => $student->getPassword(),
			"name" => $student->getName(),
			"school" => $student->getSchool(),
			"edollar" => floatval($student->getEdollar())];

        header('Content-Type: application/json');
        echo json_encode($out, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);
        exit();
	} else {
		$out = ["status" => "error" , "message" => ["invalid userid"]];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

?>