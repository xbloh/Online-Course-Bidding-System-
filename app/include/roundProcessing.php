<?php

require_once 'common.php';

$courseId = 'IS100';
$sectionId = 'S1';
$bid = new BidDAO();
$section = new SectionDAO();
$bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);

var_dump($bidByUserid);



?>
