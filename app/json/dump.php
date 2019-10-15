<?php

	require_once '../include/common.php';
	require_once '../include/token.php';

	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$studentDAO = new StudentDAO();
	$prerequisiteDAO = new PreRequisiteDAO();
	$bidDAO = new BidDAO();
	$completedCourseDAO = new CoursesCompletedDAO();
	$sectionStudentDAO = new SuccessfulBidDAO();

	$courses = $courseDAO->dump();
	$result = ['status' => 'success', 'course' => $courses];
	//var_dump($result);
	header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

?>