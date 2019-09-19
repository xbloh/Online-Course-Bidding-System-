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
		<table cellspacing="10px" cellpadding="3px">
			<?php

				if (isset($_POST['courseSelected'])) {
					$course = $_SESSION['coursesAvailable'][$_POST['indexOfCoursesToBid']];
					$sectionDAO = new SectionDAO();
					$sections = $sectionDAO->retrieveSectionsByCourse($course);
					$course->setSectionsAvailable($sections);

					echo "<tr>
							<th>
								Section ID
							</th>
							<th>
								Day
							</th>
							<th>
								Start
							</th>
							<th>
								End
							</th>
							<th>
								Instructor
							</th>
							<th>
								Venue
							</th>
							<th>
								Size
							</th>
							<th>
								Add bid
							</th>
						</tr>";
					foreach ($course->getSectionsAvailable() as $index => $section) {
						echo "<tr>
								<td>
									{$section->getSectionId()}
								</td>
								<td>
									{$section->getDay()}
								</td>
								<td>
									{$section->getStart()}
								</td>
								<td>
									{$section->getEnd()}
								</td>
								<td>
									{$section->getInstructor()}
								</td>
								<td>
									{$section->getVenue()}
								</td>
								<td>
									{$section->getSize()}
								</td>
								<td>
									Add to cart <input type='checkbox' name='indexOfSectionToBid' value='$index'>
								</td>";
					}
				}

			?>
		</table>
	</form>

</body>
</html>