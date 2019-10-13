<?php

require_once 'common.php';

$courseId = 'IS100';
$sectionId = 'S1';
$bid = new BidDAO();
$section = new SectionDAO();
$bidByUserId = $bid->bidsByCourseSection($courseId, $sectionId);
$sectionSize = $section->retrieveSectionSize($courseId,$sectionId);
$num_Bids = sizeof($bidByUserId);
echo $num_Bids; //gives 24
var_dump($sectionSize); //gives 0=> stiring '10'

?>
