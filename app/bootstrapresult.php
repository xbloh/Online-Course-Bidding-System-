<?php

	require_once 'include/common.php';
	require_once 'include/protect_admin.php';
	include 'menu_admin.php';

	echo "<h1>Round 1 started successfully</h1><br>";

	$result = $_SESSION['bootstrapresult'];
	// var_dump($result['error'][0]);
	if ($result['status'] == 'error') {
		echo "<h3>There following are the errors with bootstrap:</h3><table width='800'><tr>
		<th>file</th><th>line</th><th>errors</th></tr>";
		if(!is_array($result['error'][0])){
			echo "<tr><td></td><td></td><td align='center'>{$result['error'][0]}</td>";
		}
		else{
			foreach ($result['error'] as $error) {
					$messages = implode(', ', $error['message']);
					echo "<tr><td align='center'>{$error['file']}</td><td align='center'>{$error['line']}</td><td align='center'>{$messages}</td></tr>";
				}
		}
		echo "</table>";
	} else {
		echo "<h3>Bootstrap was successful</h3>";
	}

	echo "<h3>Records loaded: </h3><table width='400'><tr><th>file</th><th>records loaded</th></tr>";
	foreach ($result['num-record-loaded'] as $record) {
		echo "<tr>";
		foreach ($record as $file => $num) {
			echo "<td align='center'>{$file}</td><td align='center'>{$num}</td>";
		}
		echo "</tr>";
	}
	echo "</table>";

	echo "<a href = 'admin.php'>go back</a>";
?>