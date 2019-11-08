<?php
include 'menu_admin.php'
?>

<h2>Round 2 ended.</h2>
<?php
	require_once 'include/common.php';
	require_once 'include/protect_admin.php';
	
	$roundDAO = new RoundDAO();
	$roundDAO->endRound2();
echo "<a href = 'admin.php'>go back</a>";
?>
