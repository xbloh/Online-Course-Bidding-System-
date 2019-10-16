<?php

require_once 'include/common.php';

$bid = new BidDAO();
$coursedao = new CourseDAO();
$section = new SectionDAO();
$successfulBid = new SuccessfulBidDAO();
$courses = $coursedao->retrieveAllCourses();
$studentDAO = new StudentDAO();
$failBids = [];

$clearingPrice = NULL;
$succesfulBids = [];
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
// var_dump($succesfulBids);
var_dump($failBids);
foreach($succesfulBids as $successBid)
{
    $userid = $successBid[0];
    $amount = $successBid[1];
    $code = $successBid[2];
    $section = $successBid[3];
    $success = $successfulBid->successfulAddBid($userid, $amount, $code, $section);
}

foreach ($failBids as $failbid) {
    $userid = $failbid[0];
    $toAdd = $failbid[1];
    $studentDAO->addEdollar($userid, $toAdd);
}

?>
