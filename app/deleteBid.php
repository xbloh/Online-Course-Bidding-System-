<?php
require_once 'include/common.php';
$userid=$_SESSION['userid'];
$bidDAO= new BidDAO();
$CoursesSections=$bidDAO->retrieveCourseIdSectionIdBidded($userid);


?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete Bid</title>
</head>
<body>
	<form action="deleteBidProcessing.php" method="post">
		<table>
			<tr>
				<th>
					Select Course to Delete
				</th>
			</tr>
			<tr>
				<td>
					Course Id
				</td>
				<td> 
					Section Id
				</td>
				<td>
					Select to Delete
				</td>
			</tr>
						<?php
							foreach($CoursesSections as $CourseSection){
								$CourseSectionStr=$CourseSection[0].'+'.$CourseSection[1];
								echo"
								<tr>
								<td>
									{$CourseSection[0]}
								</td>
								<td>
									{$CourseSection[1]}
								</td>
								<td>
									Delete <input type='checkbox' name='deleteCourseSection' value='$CourseSectionStr'>
								</td>
								</tr>";
							}			
						?>		
				<td>
					<input type="submit" name="CourseSectionSelected" value="Submit">
				</td>
			</tr>
		</table>
	</form>

<a href='welcome.php'>Back</a>

<?php
if(isset($_SESSION['deleted'])){
	echo $_SESSION['deleted'];
}
?>

	



</body>
</html>