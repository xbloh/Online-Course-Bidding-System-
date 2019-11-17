<?php
include 'menu_admin.php';

	require_once 'include/common.php';
	require_once 'include/protect_admin.php';
	
	$roundDAO = new RoundDAO();
	$round = $roundDAO->retrieveCurrentRound();
	$status = $roundDAO->retrieveRoundStatus();

	if (!($round == 2 && $status == 'active')) {
		echo "<h1>Error: Round 2 is not active and cannot be ended</h1><br>
	    Current round: {$round}<br>
	    Round status: {$status}";
	    exit;
	}

	$roundDAO->endRound2();
	echo "<h1>Round 2 ended successfully</h1><br>";
echo "<a href = 'admin.php'>go back</a>";
?>
