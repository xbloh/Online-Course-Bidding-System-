<?php

	require_once '../include/common.php';
	require_once '../include/token.php';


	// isMissingOrEmpty(...) is in common.php
	$errors = [ isMissingOrEmpty('username'), 
	            isMissingOrEmpty('password') ];
	$errors = array_filter($errors);


	if (!isEmpty($errors)) {
	    $result = [
	        "status" => "error",
	        "messages" => array_values($errors)
	        ];
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
	            $result = ["status" => "error", "message" => "invalid password"];
	            header('Content-Type: application/json');
	            echo json_encode($result, JSON_PRETTY_PRINT);
	            exit;
	        }
	    } else {


		    $dao = new StudentDao();
		    $username_valid = $dao->isUserIdValid($username);
		    $password_valid = $dao->isPasswordValid($password);

		    if ($username_valid && $password_valid) {
		    	$result = $dao->authenticate($username, $password);
		    	if ($result[0] == "success") {
		    		$result = ["status" => "success", "token" => generate_token($username)];
		    		header('Content-Type: application/json');
	            	echo json_encode($result, JSON_PRETTY_PRINT);
		    	} else {
		    		$result = ["status" => "error", "message" => $result[0]];
		    		header('Content-Type: application/json');
	            	echo json_encode($result, JSON_PRETTY_PRINT);
		    	}
		    }

		}

	    # generate a secret token for the user based on their username

	    # return the token to the user via JSON    
			
		# return error message if something went wrong

	}

?>