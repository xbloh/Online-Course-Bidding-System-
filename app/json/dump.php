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
	$students = $studentDAO->dump();
	$sections = $sectionDAO->dump();
	$prereq = $prerequisiteDAO->dump();
	$cc = $completedCourseDAO->dump();
	$bids = $bidDAO->dump();
	$ss = $sectionStudentDAO->dump();

	$result = ['status' => 'success', 'course' => $courses, 'section' => $sections, 'student' => $students, 'prerequisite' => $prereq, 'bid' => $bids, 'completed-course' => $cc, 'section-student' => $ss];
	//var_dump($result);
	header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

?>