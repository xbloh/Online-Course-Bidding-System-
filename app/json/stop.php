<?php

	require_once '../include/common.php';

	$roundDAO = new RoundDAO();
	$status = $roundDAO->retrieveRoundStatus();
	$current = $roundDAO->retrieveCurrentRound();
	$errors = [];

	if ($status != "active" ) {
		$errors[] = "round already ended";
		$out = ["status" => "error", "message" => $errors];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRETTY_PRINT);
		exit();
	} else {
		if ($current == 2) {
			if ($roundDAO->endRound2()) {
				$out = ["status" => "success"];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		} elseif ($current == 1) {
			if ($roundDAO->endRound1()) {
				$out = ["status" => "success"];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		}
	}
?>