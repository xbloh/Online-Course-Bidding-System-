<html>
<head>
<style>
    .button {
        background-color: #1c87c9;
        border: none;
        color: white;
        padding: 16px 30px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 20px;
        margin: 4px 2px;
        cursor: pointer; 
    }

    h1 {
        font-size: 30px;
    }

    th {
        text-align: left;
    }
</style>
</head>
<?php

require_once 'include/common.php';

$student = $_SESSION['student'];
$name = $student->getName();
$userId = $student->getUserId();
$eDollar = $student->getEdollar();


$coursesCompletedDAO = new CoursesCompletedDAO();
$bidDAO = new BidDAO();
$courseDAO = new CourseDAO();
$coursesCompleted = $coursesCompletedDAO->retrieveCoursesCompleted($student);
$student->setCoursesCompleted($coursesCompleted);

$bidList = [];
$bids = $bidDAO->retrieveCourseIdSectionIdBidded($userId);
foreach($bids as $bid)
{
    $code = $bid[0];
    $section = $bid[1];
    $bidAmt = $bidDAO->retrieveBiddedAmt($userId, $code, $section);
    
    $bidList[] = [$code, $section, $bidAmt];
}
$totalAmtBid = $bidDAO->totalAmountByID($userId);
$newEDollar = $eDollar - $totalAmtBid;

?>
<body>
<h1>Welcome to BIOS, <?php echo $name; ?></h1>
<br>
<h3>You currently have <?php echo $newEDollar; ?> eDollars left</h3>
<br>
<h2>Your Successful Bid(s):<h2>

<?php
echo "<table cellspacing='10px' cellpadding='3px'>
<tr>
    <th>Course Name</th>
    <th>Course ID</th>
    <th>Section ID</th>
    <th>Bidded Amount</th>
</tr>";
foreach($bidList as $bidDisplay) {
    $displayCode = $bidDisplay[0];
    $displaySection = $bidDisplay[1];
    $displayBid = $bidDisplay[2][0];
    $course = $courseDAO->retrieveCourseById($displayCode);
    $courseName = $course->getTitle();
    echo "
    <tr>
        <td>
            $courseName
        </td>
        <td>
            $displayCode
        </td>
        <td>
            $displaySection
        </td>
        <td>
            $displayBid
        </td>
    </tr>";
}
echo "</table>";
?>

<br>
<a href ="bidPreProcessing.php" class="button" style="font-size : 20px; width: 16%; height: 20px;">Click Here to Add Bid</a>
<br>
<a href ="updateBid.php" class="button" style="font-size : 20px; width: 16%; height: 20px;">Click Here to Update Bid</a>
<br>
<a href ="deleteBid.php" class="button" style="font-size : 20px; width: 16%; height: 20px;">Click Here to Delete Bid</a>
<br>
<a href ="logout.php" class="button" style="font-size : 20px; width: 16%; height: 20px;">Logout</a>

</body>
</html>