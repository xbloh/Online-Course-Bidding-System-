<?php

require_once 'include/common.php';
require_once 'include/protect_admin.php';

$bid = new BidDAO();
$coursedao = new CourseDAO();
$sectiondao = new SectionDAO();
$courses = $coursedao->retrieveAllCourses();
$studentDAO = new StudentDAO();
$failBids = [];

$clearingPrice = NULL;
$succesfulBids = [];
foreach($courses as $course)
{
    $courseId = $course->getCourseId(); 
    $sectionIds = $sectiondao->retrieveSectionIds($courseId);
    foreach($sectionIds as $sectionId)
    {
        $bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);
        $sectionSize = $sectiondao->retrieveSectionSize($courseId,$sectionId);
        //var_dump($bidByUserid);
        if(count($bidByUserid) >= $sectionSize)
        {
            $clearingPrice = $bidByUserid[$sectionSize-1][1];
            //echo $clearingPrice;
            foreach($bidByUserid as $bidUser)
            {
                $user = $bidUser[0];
                $amount = $bidUser[1];
                if($amount > $clearingPrice)
                {
                    $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
                } elseif ($amount == $clearingPrice) {
                    if ($bidByUserid[$sectionSize][1] == $clearingPrice) {
                        $failBids[] = [$user, $amount, $courseId, $sectionId];
                    } else {
                        $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
                    }
                } else {
                    $failBids[] = [$user, $amount, $courseId, $sectionId];
                }
            }
            
        }
        else
        {
            foreach($bidByUserid as $bidUser)
            {
                $user = $bidUser[0];
                $amount = $bidUser[1];
                $succesfulBids[] = [$user, $amount, $courseId, $sectionId];
            }
        }
    }
}
//var_dump($succesfulBids);
//var_dump($failBids);
foreach($succesfulBids as $successBid)
{
    $userid = $successBid[0];
    $code = $successBid[2];
    $section = $successBid[3];
    $bidStatus = 'in';
    $bid->updateStatus($userid, $code, $section, $bidStatus);
}

foreach ($failBids as $failbid) {
    $userid = $failbid[0];
    $toAdd = $failbid[1];
    $bidStatus = 'out';
    $bid->updateStatus($userid, $code, $section, $bidStatus);
    $studentDAO->addEdollar($userid, $toAdd);
}

echo "<h1> Successful Bids:</h1>";
echo "<table cellspacing='10px' cellpadding='3px'>
<tr>
    <th>Student</th>
    <th>Course ID</th>
    <th>Section ID</th>
    <th>Bidded Amount</th>
</tr>";

$courseList = [];
foreach($succesfulBids as $successBid) {
    $courseSectionId = $successBid[2] . "-". $successBid[3];
    $courseList[$courseSectionId][] = [$successBid[0], $successBid[1], $successBid[2], $successBid[3]];
    echo "
    <tr>
        <td>
            $successBid[0]
        </td>
        <td>
            $successBid[2]
        </td>
        <td>
            $successBid[3]
        </td>
        <td>
            $successBid[1]
        </td>
    </tr>";
}
echo "</table>";
//var_dump($courseList);

foreach($courseList as $course)
{
    //var_dump($course);
    $courseId = $course[0][2];
    $sectionId = $course[0][3];
    $takenSlot = 0;
    $takenSlot = count($course);
    $availableSlot =  $sectionSize - $takenSlot;
    // var_dump([$courseId, $sectionId, $availableSlot]);
    $sectiondao->updateSize($courseId, $sectionId, $availableSlot);
}


?>
