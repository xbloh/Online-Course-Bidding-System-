<?php
include 'menu.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Place your bid!</title>
</head>
<body>
	<?php
		require 'include/common.php';
		require_once 'include/protect.php';
		$roundDAO = new RoundDAO();
		$rndStatus = $roundDAO->retrieveRoundStatus();
					if($rndStatus=='completed')
					{
						echo "<h3>The round has ended</h3><br><br><a href='welcome.php'>Go back to Home</a>";
					}
					elseif($rndStatus=='Begin')
					{
						echo "<h3>The round has not begin</h3><br><br><a href='welcome.php'>Go back to Home</a>";
					}
					else
					{
							echo"<form action='addBid.php' method='post'>
							<table>
								<tr>
									<th>
										Select course:
									</th>
									<td>
									<select name='indexOfCourseToBid'>";
						
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
					echo'</select>
				</td>
				<td>
					<input type="submit" name="courseSelected" value="View Course">
				</td>
			</tr>
		</table>
	</form>';
	}		
	?>

	<form method='post'>
		<table cellspacing="10px" cellpadding="3px">
			<?php

				// $roundDAO = new RoundDAO();
				$currentRnd = $roundDAO->retrieveCurrentRound();
				$rndStatus = $roundDAO->retrieveRoundStatus();

				if (isset($_POST['courseSelected'])) {

					$course = $_SESSION['coursesAvailable'][$_POST['indexOfCourseToBid']];
					$_SESSION['courseSelected'] = $course;
					$sectionDAO = new SectionDAO();
					$bidDAO = new BidDAO();
					$sections = $sectionDAO->retrieveSectionIdsByCourse($course);
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
								Vacancy
							</th>";
					if(($currentRnd == '2' && $rndStatus == 'active') || $rndStatus == 'completed')
					{
						echo "<th>Minimum Bid</th>";
					}
					echo "
						<th>Add bid</th>
						</tr>";

					foreach ($course->getSectionsAvailable() as $index => $sectionId) {
						$section = $sectionDAO->retrieveSection($course->getCourseId(), $sectionId);
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
								<td align = 'center'>
									{$section->getSize()}
								</td>";
								if(($currentRnd == '2' && $rndStatus == 'active') || $rndStatus == 'completed')
								{
									$bidDAO = new BidDAO();
									$winList = $bidDAO->winBids($course->getCourseId(), $section->getSectionId(), $section->getSize(), $currentRnd);
									$minBidAmt = $bidDAO->minBid($course->getCourseId(), $section->getSectionId(), $section->getSize(), 2, $winList);
									echo "<td>$minBidAmt</td>";
								}
								echo "
									<td>
										Add to cart <input type='radio' name='indexOfSectionToBid' value='$index' $selected>
									</td>
								</tr>";
					}
					echo "</table>
					<input type='submit' name='sectionSelected' value='Select Section'>";
				}

			?>
		
	</form>
	<?php
		if(isset($_POST['sectionSelected']) && isset($_POST['indexOfSectionToBid'])) {
			$course = $_SESSION['courseSelected'];
			$index = $_POST['indexOfSectionToBid'];
			$sectionSelected = [$course->getCourseId(), $course->getSectionsAvailable()[$index]];
			// $student=$_SESSION['student'];
			// $courseId1=$course->getCourseId();
			// $sessionId1=$sectionSelected->getSectionId();
			// $userId=$student->getUserId();
			// $amount=0;
			// $bid = new Bid($userId, $amount, $courseId1, $sessionId1, $isAddBid = True); 

			if(!isset($_SESSION['cart'])){
				// if(!empty($bid->validate())){
				// 	$_SESSION['errors']=$bid->validate();
				// 	printErrors();
				// }
				// else{
				// 	$_SESSION['cart']= [$sectionSelected];
				// }
				$_SESSION['cart'] = [$sectionSelected];
			}

			else{
			// var_dump($sectionSelected->getCourse());
			// foreach($_SESSION['cart'] as $cart){
			// 	$cartCourse=$cart->getCourse();
			// 	$sessionId=$cart->getSectionId();
			// 	$courseId=$cartCourse->getCourseId();
			// 	$bid = new Bid($userId, $amount, $courseId, $sessionId, $isAddBid = True); 
			// 	// var_dump($bid->validate());
			// 	if(empty($bid->validate())){
			// 		if($cartCourse->getCourseId()==$course->getCourseId() && $cart->getSectionId()==$sectionSelected->getSectionId()){
						
			// 		}
			// 		else{
			// 			$_SESSION['cart'][] = $sectionSelected;
			// 		}
			// 	}
			// 	else{
			// 		$_SESSION['errors']=$bid->validate();
			// 	}
			// }	
				if (!in_array($sectionSelected, $_SESSION['cart'])) {
					$_SESSION['cart'][] = $sectionSelected;
				}
			}
		}
		// var_dump($_SESSION['errors']);
		// var_dump($_SESSION['cart']);

		if(isset($_SESSION['cart'])){
			if(!isset($_SESSION['errors'])){
			echo "<form action = 'placeBids.php' method = 'POST'>
					<input type = 'submit' name = 'placeBids' value = 'Place Bids'>
					</form>";
			}
			else{
				echo "<form action = 'placeBids.php' method = 'POST'>
						<input type = 'submit' name = 'placeBids' value = 'Place Bids'>
						</form>";
				printErrors();
			}
		}

		// }
	?>



</body>
</html>