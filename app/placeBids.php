<?php
	// for all bid validations
	require_once 'include/common.php';
	require_once 'include/protect.php';
	
	$sectionDAO = new SectionDAO();
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
		if($sectionSelected!=NULL){
			$identifier = $sectionSelected[0] . $sectionSelected[1];
			$courseId = $sectionSelected[0];
			$sectionId = $sectionSelected[1];
			$section = $sectionDAO->retrieveSection($courseId, $sectionId);
			echo "<form action='placeBidsProcessing.php' method='post'>
			<tr>
				<td>
					{$courseId}
				</td>
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
					<input type='text' name='$identifier'>
				</td>

			</tr>";
		}
	}

?>
<tr>
</tr>
<tr>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="submit" name="submit"></td>
</tr>
</form>