<?php
	require_once 'token.php';

	$errors = [];
	
	if (isset($_REQUEST['token'])) {
		$token = $_REQUEST['token'];
		if ($token == '') {
			$errors[] = 'blank token';
		} elseif (!verify_token($token) == 'admin') {
			$errors[] = 'invalid token';
		}
	} else {
		$errors[] = 'missing token';
	}

	if (count($errors) > 0) {
		$out = ["status" => "error" , "message" => $errors];
		header('Content-Type: application/json');
        echo json_encode($out, JSON_PRETTY_PRINT);
        exit;
	}

?>