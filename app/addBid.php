<!DOCTYPE html>
<html>
<head>
	<title>Place your bid!</title>
</head>
<body>
	<form action="bidProcessing.php" method="post">
		<select name='indexofCoursesToBid'>
				<?php
					require 'include/common.php';
					foreach ($_SESSION['coursesAvailable'] as $index => $course) {
						echo "<option value='$index'>" . $course->getCourseId() . " " . $course->getTitle() . "</option>";
					}
				?>
		</select>
	</form>

</body>
</html>