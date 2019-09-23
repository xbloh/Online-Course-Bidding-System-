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
					<select name='indexOfCourseToBid'>
						<?php
							require 'include/common.php';
							foreach ($_SESSION['coursesAvailable'] as $index => $course) {
								
								$preReqDAO = new PreRequisiteDAO();
								$preRequisites = $preReqDAO->retrievePreRequisites($course);
								$course->setPreRequisites($preRequisites);


								if (isset($_POST['indexOfCourseToBid']) && $index == $_POST['indexOfCourseToBid']) {
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
					<input type="submit" name="courseSelected" value="View Course">
				</td>
			</tr>
		</table>
	</form>

	<form method='post'>
		<table cellspacing="10px" cellpadding="3px">
			<?php

				if (isset($_POST['courseSelected'])) {

					$course = $_SESSION['coursesAvailable'][$_POST['indexOfCourseToBid']];
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
						if (isset($_POST['indexOfSectionToBid']) && $index == $_POST['indexOfSectionToBid']) {
									$selected = 'checked';

						}else {
							$selected = '';
						}
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
									Add to cart <input type='checkbox' name='indexOfSectionToBid' value='$index' $selected>
								</td>";
					}
				}

			?>
		</table>
		<input type="submit" name="sectionSelected" value="Select Section">
	</form>

</body>
</html>