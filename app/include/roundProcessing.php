<?php

require_once 'common.php';

$courseId = 'IS100';
$sectionId = 'S1';
$bid = new BidDAO();
$coursedao = new CourseDAO();
$section = new SectionDAO();

$courses = $coursedao->retrieveAllCourses();
foreach($courses as $course)
{
    $courseId = $course->getCourseId(); 
    $sectionId = $section->retrieveSectionIdsByCourse($courseId);

    $bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);
    $sectionSize = $section->retrieveSectionSize($courseId,$sectionId);
}
var_dump($bidByUserid);


var_dump($bidByUserid);
$count = 1;
$clearingPrice = NULL;
$result = [];
for($i = 0; $i < $sectionSize; $i++)
{
    // echo "{$bidByUserid[$i][1]}";
    // echo "<br>";
    $clearingPrice = $bidByUserid[$sectionSize][1];
}

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
