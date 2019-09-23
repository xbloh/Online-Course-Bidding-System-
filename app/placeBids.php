<?php
// for all bid validations
require_once 'include/common.php';
// ignore validations first
echo "<h1>Your Selected Section(s)<h1>";
			echo "<table cellspacing='10px' cellpadding='3px'>
			<tr>
			<th>Course ID</th>
			<th>Section ID</th>
			<th>Day</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Instructor</th>
			<th>Venue</th>
			<th>Size</th>
			<th>Bid Amount</th>
			</tr>";
			//var_dump($_SESSION['cart']);
			foreach($_SESSION['cart'] as $sectionSelected) {
				$identifier = $sectionSelected->getCourseId() . $sectionSelected->getSectionId();
				echo "<form action='placeBidsProcessing.php' method='post'>
				<tr>
					<td>
						{$sectionSelected->getCourseId()}
					</td>
					<td>
						{$sectionSelected->getSectionId()}
					</td>
					<td>
						{$sectionSelected->getDay()}
					</td>
					<td>
						{$sectionSelected->getStart()}
					</td>
					<td>
						{$sectionSelected->getEnd()}
					</td>
					<td>
						{$sectionSelected->getInstructor()}
					</td>
					<td>
						{$sectionSelected->getVenue()}
					</td>
					<td>
						{$sectionSelected->getSize()}
					</td>
					<td>
						<input type='text' name='$identifier'>
					</td>

				</tr>";
			}

?>
<tr>
</tr>
<tr>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="submit" name="submit"></td>
</tr>
</form>