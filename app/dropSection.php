<?php

	require_once 'include/common.php';
	require_once 'include/protect.php';
	
	$bidDAO = new BidDAO();
	$userId = $_SESSION['student']->getUserId();

	$successfulBids = $bidDAO->retrieveUserSuccessfulBids($userId);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Drop Section</title>
</head>
<body>
	<h2>Select section to drop</h2>
	<form action="dropSectionProcessing.php">
		<table cellspacing="10px" cellpadding="3px">
			<tr>
				<th>Course Id</th>
				<th>Section Id</th>
				<th>Select to drop</th>
			</tr>
		
		<?php
			foreach ($successfulBids as $bid) {
				$identifier = $bid['code'] . ',' . $bid['section'];
				$courseId = $bid['code'];
				$sectionId = $bid['section'];
				echo "<tr><td>{$courseId}</td><td>{$sectionId}</td>
				<td><input type='checkbox' name='toDrop[]' value={$identifier}></td></tr>";
			}
		?>
		</table>
		<input type="submit" name="Submit">
	</form>
	
</body>
</html>