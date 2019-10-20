<?php

	include '../include/common.php';

	$request = json_decode($_REQUEST['r']);

	$studentDAO = new StudentDAO();

	$is_valid = $studentDAO->isUserIdValid($request->userid);

	if ($is_valid) {
		$student = $studentDAO->retrieveStudentByUserId($request->userid);
		$out = [
			"status" => "success",
			"userid" => $student->getUserId(),
			"password" => $student->getPassword(),
			"name" => $student->getName(),
			"school" => $student->getSchool(),
			"edollar" => $student->getEdollar()];

        header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	} else {
		$out = ["status" => "error" , "message" => ["invalid userid"]];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit();
	}

?>