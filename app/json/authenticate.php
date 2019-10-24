<?php

	require_once '../include/common.php';
	require_once '../include/token.php';


	// isMissingOrEmpty(...) is in common.php
	$errors =  [isMissingOrEmpty('username'), 
	            isMissingOrEmpty('password')];
	$errors = array_filter($errors);


	if (!isEmpty($errors)) {
	    $result = [
	        "status" => "error",
	        "message" => array_values($errors)];
	        header('Content-Type: application/json');
	        echo json_encode($result, JSON_PRETTY_PRINT);
	        exit;
	}
	else{
	    $username = $_POST['username'];
	    $password = $_POST['password'];

	# complete authenticate API

	    # check if username and password are right. generate a token and return it in proper json format
	    if ($username === "admin") {
	        if ($password === "@dm1n5PM") {
	            $result = ["status" => "success", "token" => generate_token($username)];
		    	header('Content-Type: application/json');
	            echo json_encode($result, JSON_PRETTY_PRINT);
	            exit;
	        } else {
	            $result = ["status" => "error", "message" => ["invalid password"]];
	            header('Content-Type: application/json');
	            echo json_encode($result, JSON_PRETTY_PRINT);
	            exit;
	        }
	    } else {
	    	$result = ["status" => "error", "message" => ["invalid username"]];
            header('Content-Type: application/json');
            echo json_encode($result, JSON_PRETTY_PRINT);
            exit;

		}

	}

?>