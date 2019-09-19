<!DOCTYPE html>
<html>
<head>
	<title>Place your bid!</title>
</head>
<body>
	<form action="addBid.php" method="post">
		<table>
			<tr>
				<th>
					Select course to bid for:
				</th>
				<td>
					<select name='indexOfCoursesToBid'>
						<?php
							require 'include/common.php';
							foreach ($_SESSION['coursesAvailable'] as $index => $course) {
								if (isset($_POST['indexOfCoursesToBid']) && $index == $_POST['indexOfCoursesToBid']) {
									$selected = 'selected';

								}else {
									$selected = '';
								}

								echo "<option value='$index' $selected>" . $course->getCourseId() . " " . $course->getTitle() . "</option>";
							}
						?>
					</select>
				</td>
				<td>
					<input type="submit" name="courseSelected" value="Select">
				</td>
			</tr>
		</table>
	</form>

	<?php

		if (isset($_POST['courseSelected'])) {
			# code...
		}

	?>

</body>
</html>