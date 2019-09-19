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
					Select course:
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

	<form>
		<table>
			<tr>
				<th>
					Select Section:
				</th>
				<td>
					<select name="indexOfSectionToBid">
					<?php

						if (isset($_POST['courseSelected'])) {
							$course = $_SESSION['coursesAvailable'][$_POST['indexOfCoursesToBid']];
							$sectionDAO = new SectionDAO();
							$sections = $sectionDAO->retrieveSectionsByCourse($course);
							$course->setSectionsAvailable($sections);

							foreach ($course->getSectionsAvailable() as $index => $section) {
								echo "<option value='$index'>" . $section->getSectionId() . "</option>";
							}
						}

					?>
					</select>
				</td>
			</tr>
		</table>
	</form>


</body>
</html>