 <?php

require_once 'include/common.php';
include 'menu.php';
require_once 'include/protect.php';

$student = $_SESSION['student'];
$name = $student->getName();
$userId = $student->getUserId();

$coursesCompletedDAO = new CoursesCompletedDAO();
$bidDAO = new BidDAO();
$courseDAO = new CourseDAO();
$studentDAO = new StudentDAO();
$sectionDAO = new SectionDAO();
$eDollar = $studentDAO->retrieveStudentByUserId($userId)->getEdollar();
$coursesCompleted = $coursesCompletedDAO->retrieveCoursesCompleted($student);
$student->setCoursesCompleted($coursesCompleted);

$roundDAO = new RoundDAO();
$currentRnd = $roundDAO->retrieveCurrentRound();
$rndStatus = $roundDAO->retrieveRoundStatus();

$bidList = [];
$bids = $bidDAO->retrieveCourseIdSectionIdBidded($userId);
foreach($bids as $bid)
{
    $code = $bid[0];
    $section = $bid[1];
    $bidAmt = $bidDAO->retrieveBiddedAmt($userId, $code, $section);
    $vacancy = $sectionDAO->retrieveSectionSize($code,$section);
    // echo $code,$section,$vacancy;
    $winList = $bidDAO->winBids($code, $section, $vacancy, 2);
    // var_dump($winList);
    $minBidAmt = $bidDAO->minBid($code, $section, $vacancy, 2, $winList);
    // var_dump($minBidAmt);
    $result = $bid[2];
    // var_dump($bid);
    $status = 'Unsuccessful';

    if($result == '-')
    {
        $status = 'Pending';
    }

    if($result=='in')
    {
        $status = 'Successful';
    }
	if(($currentRnd == '2' && $rndStatus == 'active') || $rndStatus == 'completed')
	{
        if($bid[3]=='1')
        {
            $bidList[] = [$code, $section, $bidAmt, $status, $minBidAmt];
        }
        else
        {
            $bidList[] = [$code, $section, $bidAmt, $status, $minBidAmt];
        }
    }
    else
    {
        $bidList[] = [$code, $section, $bidAmt];
    }
}

?>
<body>
<h1>Welcome to BIOS, <?php echo $name; ?></h1>
<br>
<h2>Round <font style = "color:#1c87c9"><?php echo $roundDAO->retrieveCurrentRound();?></font> is currently <font style = "color:#1c87c9"><?php echo $roundDAO->retrieveRoundStatus();?></font></h2>
<h3>You currently have <?php echo $eDollar; ?> eDollars left</h3>
<br>


<?php
if($currentRnd == '2' || $rndStatus == 'completed')
{
    echo "<h2>Your Bidding Result(s):<h2>";
}
else
{
    echo "<h2>Your Placed Bid(s):<h2>";
}
echo "<table cellspacing='10px' cellpadding='3px'>
<tr>
    <th>Course Name</th>
    <th>Course ID</th>
    <th>Section ID</th>
    <th>Bidded Amount</th>
    ";
if($currentRnd == '2' && $rndStatus == 'active')
{
    echo "<th>Bid Status</th>
          <th>Minimum Bid</th>";
}
if($currentRnd == '1' && $rndStatus == 'active')
{
    echo "<th>Bidding Status</th>";
}
if($currentRnd == '1' && $rndStatus == 'completed')
{
    echo "<th>Bid Status</th>";
}
echo"</tr>";

foreach($bidList as $bidDisplay) {
    $displayCode = $bidDisplay[0];
    $displaySection = $bidDisplay[1];
    $displayBid = $bidDisplay[2];
    $course = $courseDAO->retrieveCourseById($displayCode);
    $courseName = $course->getTitle();
    
    if($currentRnd == '2' && $rndStatus == 'active')
    {
        $number = number_format($bidDisplay[4],2,'.','');
        echo "
            <tr>
            <th>
                $courseName
            </th>
            <th>
                $displayCode
            </th>
            <th>
                $displaySection
            </th>
            <th>
                $displayBid
            </th>
            <th>
                {$bidDisplay[3]}
            </th>
            <th>
                {$number}
            </th>";
    }
    elseif($currentRnd == '1' && $rndStatus == 'active')
    {
        echo "
        <tr>
            <th>
                $courseName
            </th>
            <th>
                $displayCode
            </th>
            <th>
                $displaySection
            </th>
            <th>
                $displayBid
            </th>
            <th>
                $status
            </th>
            ";
    }
    
    elseif($currentRnd == '1' && $rndStatus == 'completed')
    {
        echo "
        <tr>
            <th>
                $courseName
            </th>
            <th>
                $displayCode
            </th>
            <th>
                $displaySection
            </th>
            <th>
                $displayBid
            </th>
            <th>
                {$bidDisplay[3]}
            </th>
            ";
    }
    echo "</tr>";
}
echo "</table>";
?>

<!-- <br>
<a href ="bidPreProcessing.php" class="button" style="font-size : 20px; width: 30%; height: 20px;">Click Here to Add Bid</a>
<br>
<a href ="updateBid.php" class="button" style="font-size : 20px; width: 30%; height: 20px;">Click Here to Update Bid</a>
<br>
<a href ="deleteBid.php" class="button" style="font-size : 20px; width: 30%; height: 20px;">Click Here to Delete Bid</a>
<br>
<a href ="dropSection.php" class="button" style="font-size : 20px; width: 30%; height: 20px;">Click Here to Drop a Section</a>
<br>
<a href ="logout.php" class="button" style="font-size : 20px; width: 30%; height: 20px;">Logout</a> -->

</body>
</html>

