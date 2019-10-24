<?php

// Session should have a student object stored in it (need research, apparently session storing objects can't work well with huge traffic flow)

require_once 'include/common.php';

$student = $_SESSION['student'];

// for round 1 bidding i.e. $isRound1 == True, for now just set as true
$roundDAO = new RoundDAO();
$status = $roundDAO->retrieveRoundStatus();
if ($status == 'active') {
	$round = $roundDAO->retrieveCurrentRound();
} else {
	$round = 0;
}

$courseDAO = new CourseDAO();

if ($round == 1) {
	$school = $student->getSchool();
	$coursesAvailable = $courseDAO->retrieveCoursesBySchool($school);

} elseif ($round == 2) {
	$coursesAvailable = $courseDAO->retrieveAllCourses();
} else {
	$coursesAvailable = [];
}

//courses available will be added as an array of course objects into SESSION
$_SESSION['coursesAvailable'] = $coursesAvailable;

header("Location: addBid.php");


?>