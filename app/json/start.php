<?php
	require_once '../include/common.php';
	include '../include/json-protect.php';

	$roundDAO = new RoundDAO();
	$status = $roundDAO->retrieveRoundStatus();
	$current = $roundDAO->retrieveCurrentRound();
	$errors = [];

	if ($status == "active" ) {
		$out = ["status" => "success", "round" => $current];
		header('Content-Type: application/json');
		echo json_encode($out, JSON_PRETTY_PRINT);
		exit();
	} else {
		if ($current == 2) {
			$errors[] = "round 2 ended";
			$out = ["status" => "error", "message" => $errors];
			header('Content-Type: application/json');
			echo json_encode($out, JSON_PRETTY_PRINT);
		} elseif ($current == 1) {
			if ($roundDAO->startRound2()) {
				$out = ["status" => "success", "round" => $current + 1];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		} else {
			if ($roundDAO->startRound1()) {
				$out = ["status" => "success", "round" => $current + 1];
				header('Content-Type: application/json');
				echo json_encode($out, JSON_PRETTY_PRINT);
			}
		}
	}

?>