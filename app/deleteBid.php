<!DOCTYPE html>
<html>
<head>
	<title>Delete Bid</title>
</head>
<?php
require_once 'include/common.php';
require_once 'include/protect.php';

$userid=$_SESSION['userid'];
$bidDAO= new BidDAO();
$CoursesSections=$bidDAO->retrieveCourseIdSectionIdBidded($userid);
if($CoursesSections==[])
{
	echo "<h2>No more course bidded.</h2>";
}
else
{
echo"
<body>
	<form action='deleteBidProcessing.php' method='post'>
	<h2>Select Course to Delete</h2>
		<table cellspacing='10px' cellpadding='3px'>
			<tr>
				<th>
					Course Id
				</th>
				<th> 
					Section Id
				</th>
				<th>
					Select to Delete
				</th>
			</tr>";
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
							Delete <input type='radio' name='deleteCourseSection' value='$CourseSectionStr'>
						</td>
						</tr>";
					}				
				echo '<td>
					<input type="submit" name="CourseSectionSelected" value="Submit">
				</td>
			</tr>
		</table>
	</form>';
}
?>
<a href='welcome.php'>Back</a>
<br>

<?php
if(isset($_SESSION['deleted'])){
	echo $_SESSION['deleted'];
	unset($_SESSION['deleted']);
}
?>

</body>
</html>