<?php
include 'menu_admin.php';

    require_once 'include/common.php';
    $roundDAO = new RoundDAO();
    $round = $roundDAO->retrieveCurrentRound();
    $status = $roundDAO->retrieveRoundStatis();

    if (!($round == 1 && $status == $completed)) {
    	echo "<h1>Error: Round 2 cannot be started!</h1><br>
	    Current round: {$round}<br>
	    Round status: {$status}";
	    exit;
    }

    $roundDAO->startRound2();
    echo "<h1>Round 2 started successfully</h1><br>";

echo "<a href = 'admin.php'>go back</a>";
?>