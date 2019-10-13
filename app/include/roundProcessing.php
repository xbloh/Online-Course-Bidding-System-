<?php

require_once 'common.php';

$courseId = 'IS100';
$sectionId = 'S1';
$bid = new BidDAO();
$section = new SectionDAO();
$bidByUserid = $bid->bidsByCourseSection($courseId, $sectionId);

var_dump($bidByUserid);
$count = 1;
$clearingPrice = NULL;
$result = [];
for($i = 0; $i < 10; $i++)
{
    // echo "{$bidByUserid[$i][1]}";
    // echo "<br>";
    $clearingPrice = $bidByUserid[10][1];
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
