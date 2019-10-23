<?php
	require_once 'include/common.php';

	$toDrop = [];
	$bidDAO = new BidDAO();
	$request = $_GET['toDrop'];
	$errors = [];
	$studentDAO = new StudentDAO();

	if (count($request) == 0) {
		echo "<h2>No bids selected</h2>
		<a href='welcome.php'>Go to homepage</a>";
	} else {
		foreach ($request as $identifier) {
			$toDrop[] = explode(',', $identifier);
		}

		$userId = $_SESSION['student']->getUserId();

		foreach ($toDrop as $bid) {
			$amount = $bidDAO->retrieveBiddedAmt($userId, $bid[0], $bid[1]);
			$studentDAO->addEdollar($userId, $amount);
			if (!$bidDAO->deleteBid($userId, $bid[0], $bid[1])) {
				$errors[] = $bid;
			}
		}

		if (count($errors) > 0) {
			echo "There was an error with the following sections:</br>";
			foreach ($errors as $bid) {
				$out = implode(' ', $bid);
				echo "$out<br>";
			}
		} else {
			echo "All sections deleted successfully</br>";
		}
		echo "<a href='welcome.php'>Go to homepage</a>";
	}

?>