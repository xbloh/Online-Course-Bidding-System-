<?php
	require_once 'include/common.php';
	require_once 'include/protect_admin.php';
	
	$roundDAO = new RoundDAO();
	$roundDAO->endRound2();

?>
<h2>Round 2 ended.</h2>