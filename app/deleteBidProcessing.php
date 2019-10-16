<?php
require_once 'include/common.php';
$userId=$_SESSION['student']->getUserId();
// $section='S1';
// $course='IS102';
$bidDAO= new BidDAO();
// $bidDAO->deleteBid($userId, $course, $section);
// var_dump($_POST['deleteCourseSection']);
if(isset($_POST['deleteCourseSection']))
{
    // foreach($_POST['deleteCourseSection'] as $check){
    $CourseSection=explode('+', $_POST['deleteCourseSection']);
    $deleteStatus=$bidDAO->deleteBid($userId, $CourseSection[0], $CourseSection[1]);
    if($deleteStatus){
        $_SESSION['deleted']="You have deleted the bid.";
        header('Location: deleteBid.php');
    }
    }
else
{
    $_SESSION['deleted']="Please Select a Bid.";
    header('Location: deleteBid.php');
}
?>