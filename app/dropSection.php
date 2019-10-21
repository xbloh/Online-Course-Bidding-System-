<?php

	require_once 'include/common.php';
	$bidDAO = new BidDAO();
	$userId = $_SESSION['student']->getUserId();

	$successfulBids = $bidDAO->retrieveUserSuccessfulBids($userId);
	var_dump($successfulBids);

?>