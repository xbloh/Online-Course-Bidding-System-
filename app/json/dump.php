<?php

	require_once '../include/common.php';
	include '../include/json-protect.php';

	$courseDAO = new CourseDAO();
	$sectionDAO = new SectionDAO();
	$studentDAO = new StudentDAO();
	$prerequisiteDAO = new PreRequisiteDAO();
	$bidDAO = new BidDAO();
	$completedCourseDAO = new CoursesCompletedDAO();

	$courses = $courseDAO->dump();
	$students = $studentDAO->dump();
	$sections = $sectionDAO->dump();
	$prereq = $prerequisiteDAO->dump();
	$cc = $completedCourseDAO->dump();
	$bids = $bidDAO->dump();
	$ss = $bidDAO->ssDump();

	$result = ['status' => 'success', 'course' => $courses,	'section' => $sections, 'student' => $students, 'prerequisite' => $prereq, 'bid' => $bids, 'completed-course' => $cc, 'section-student' => $ss];
	// foreach ($courses as $key => $value) {
	// 	$result['course'][] = $value;
	// }
	//var_dump($result);

	header('Content-Type: application/json');
    echo json_encode($result, JSON_PRESERVE_ZERO_FRACTION | JSON_PRETTY_PRINT);

?>