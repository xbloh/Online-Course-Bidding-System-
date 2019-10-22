<?php

// Session should have a student object stored in it (need research, apparently session storing objects can't work well with huge traffic flow)

require_once 'include/common.php';
//require_once 'include/protect.php';

$student = $_SESSION['student'];

// for round 1 bidding i.e. $isRound1 == True, for now just set as true
$isRound1 = True;
$courseDAO = new CourseDAO();

if ($isRound1) {
	$school = $student->getSchool();
	$coursesAvailable = $courseDAO->retrieveCoursesBySchool($school);

} elseif ($isRound2) {
	$coursesAvailable = $courseDAO->retrieveAllCourses();
}

//courses available will be added as an array of course objects into SESSION
$_SESSION['coursesAvailable'] = $coursesAvailable;

header("Location: addBid.php");


?>