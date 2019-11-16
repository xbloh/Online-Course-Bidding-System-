<?php

	require_once 'include/common.php';
	require_once 'include/protect.php';
	include 'menu.php';
	$bidDAO = new BidDAO();
	$roundDAO = new RoundDAO();
	$rndStatus = $roundDAO->retrieveRoundStatus();
	$userId = $_SESSION['student']->getUserId();

	$successfulBids = $bidDAO->retrieveUserSuccessfulBids($userId);
	echo '<!DOCTYPE html>
	<html>
	<head>
		<title>Drop Section</title>
	</head>
	<body>';

	if($rndStatus=='completed')
	{
		echo "<h3>The round has ended</h3><br><br><a href='welcome.php'>Go back to Home</a>";
	}
	elseif($rndStatus=='Begin')
	{
		echo "<h3>The round has not begin</h3><br><br><a href='welcome.php'>Go back to Home</a>";
	}
	else
	{
	echo '
		<h2>Select section to drop</h2>
		<form action="dropSectionProcessing.php">
			<table cellspacing="10px" cellpadding="3px">
				<tr>
					<th>Course Id</th>
					<th>Section Id</th>
					<th>Select to drop</th>
				</tr>';

			foreach ($successfulBids as $bid) {
				$identifier = $bid['code'] . ',' . $bid['section'];
				$courseId = $bid['code'];
				$sectionId = $bid['section'];
				echo "<tr><td>{$courseId}</td><td>{$sectionId}</td>
				<td><input type='checkbox' name='toDrop[]' value={$identifier}></td></tr>";
			}
		echo "</table>";
		if(count($successfulBids)>0){
			echo ' 
			<input type="submit" name="Submit">
			</form>';
		}
		else{
			echo "<a href='welcome.php'>Go back to Home</a>";
		}
	}
	?>
	
</body>
</html>