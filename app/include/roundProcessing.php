<?php

require_once 'common.php';

$bid = new BidDAO();
$coursedao = new CourseDAO();
$section = new SectionDAO();

$courses = $coursedao->retrieveAllCourses();
$clearingPrice = NULL;
foreach($courses as $course)
{
    $courseId = $course->getCourseId(); 
    $sectionIds = $section->retrieveSectionIds($courseId);
    foreach($sectionIds as $sectionId)
    {
        $bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);
        $sectionSize = $section->retrieveSectionSize($courseId,$sectionId);
        var_dump($bidByUserid);
        if(count($bidByUserid) >= $sectionSize)
        {
            $clearingPrice = $bidByUserid[$sectionSize][1];
            echo $clearingPrice;
        }
    }
}

// $clearingPrice = NULL;
// var_dump($clearingPrice);
// $result = [];
// for($i = 0; $i < $sectionSize; $i++)
// {
//     // echo "{$bidByUserid[$i][1]}";
//     // echo "<br>";
//     $clearingPrice = $bidByUserid[$size][1];
// }

$succesfulBids = [];
foreach($bidByUserid as $bid)
{
    $user = $bid[0];
    $amount = $bid[1];
    if($amount > $clearingPrice)
    {
        $succesfulBids[] = [$user, $amount];
    }
}


var_dump($succesfulBids);

?>
