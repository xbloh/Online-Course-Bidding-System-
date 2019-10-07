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
        cursor: pointer; }

    h1 {
        font-size: 30px;
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
//var_dump($_SESSION);
//var_dump($bids);
//var_dump($bidList);

?>
<body>
<h1>Welcome to BOIS, <?php echo $name; ?></h1>
<br>
<h3>You currently have <?php echo $newEDollar; ?> eDollars</h3>
<br>

<form action="updateBid.php" method="POST">
<?php
echo "<h1>Your Successful Bid(s)<h1>";
echo "<table cellspacing='10px' cellpadding='3px'>
<tr>
<th>Course ID</th>
<th>Section ID</th>
<th>Bid Amount</th>
</tr>";
foreach($bidList as $bidDisplay) {
    $displayCode = $bidDisplay[0];
    $displaySection = $bidDisplay[1];
    $displayBid = $bidDisplay[2][0];
    echo "
    <tr>
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
?>
</form>








<br>
<a href ="bidPreProcessing.php" class="button">Click Here to Add Bid</a>
<br>
<a href ="deleteBid.php" class="button">Click Here to Delete Bid</a>
</body>
</html>