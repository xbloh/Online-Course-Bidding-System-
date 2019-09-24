<?php
require_once 'include/common.php';

function doBootstrap() {
		

	$errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

	# keep track of number of lines successfully processed for each file
	$Student_processed=0;
	$Bid_processed=0;
    $Course_completed_processed=0;
    $Course_processed=0;
    $Prerequisite_processed=0;
    $Section_processed=0;


	# check file size
	if ($_FILES["bootstrap-file"]["size"] <= 0)
		$errors[] = "input files not found";

	else {
		
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);

		if ($res === TRUE) {
			$zip->extractTo($temp_dir);
			$zip->close();
		
			$bid_path = "$temp_dir/bid.csv";
			$course_completed_path = "$temp_dir/course_completed.csv";
            $prerequisite_path = "$temp_dir/prerequisite.csv";
            $section_path = "$temp_dir/section.csv";
            $course_path = "$temp_dir/course.csv";
            $student_path = "$temp_dir/student.csv";

			
			$bid = @fopen($bid_path, "r");
			$course_completed = @fopen($course_completed_path, "r");
            $course = @fopen($course_path, "r");
            $prerequisite = @fopen($prerequisite_path, "r");
            $student = @fopen($student_path, "r");
            $section = @fopen($section_path, "r");
			
			if (empty($bid) || empty($course_completed) || empty($course) || empty($prerequisite) || empty($section) || empty($student)){
				$errors[] = "input files not found";
				if (!empty($bid)){
					fclose($bid);
					@unlink($bid_path);
				} 
				
				if (!empty($course_completed)) {
					fclose($course_completed);
					@unlink($course_completed_path);
				}
				
				if (!empty($course)) {
					fclose($course);
					@unlink($course_path);
                }
                if (!empty($prerequisite)){
					fclose($prerequisite);
					@unlink($prerequisite_path);
				} 
				
				if (!empty($section)) {
					fclose($section);
					@unlink($section_path);
				}
				
				if (!empty($student)) {
					fclose($student);
					@unlink($student_path);
				}
				
				
			}
			else {
				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();

				#start processing
                #truncate current SQL tables
                $sectionDAO = New SectionDAO();
                $sectionDAO->removeAll();

                #then read each csv file line by line (remember to skip the header)
				#$data = fgetcsv($file) gets you the next line of the CSV file which will be stored 
				#in the array $data
				#$data[0] is the first element in the csv row, $data[1] is the 2nd, ....
				$data = fgetcsv($section);
				#to remove the first line, because it rmbs which line it reads
                
                while(($data = fgetcsv($section)) != false){
                    #0 -> course, #1 -> section, #2 -> day, #3 -> start, #4 -> end, #5 -> instructor, #6 -> venue, #7 -> size
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) || !empty($data[1]) || !empty($data[2]) || !empty($data[3]) || !empty($data[4]) || !empty($data[5]) || !empty($data[6]) || !empty($data[7])){
                        $new_section = new Section($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
                        $sectionDAO->add($new_section);
                        $section_processed++;
                    }
                }

                $courseDAO = New CourseDAO();
                $courseDAO->removeAll();
                $data = fgetcsv($course);
                while(($data = fgetcsv($course)) != false){
                    #0 -> course, #1 -> school, #2 -> title, #3 -> description, #4 -> exam date, #5 -> exam start, #6 -> exam end
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) || !empty($data[1]) || !empty($data[2]) || !empty($data[3]) || !empty($data[4]) || !empty($data[5]) || !empty($data[6])){
                        $new_course = new Course($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                        $courseDAO->add($new_course);
                        $course_processed++;
                    }
                }

                $studentDAO = New StudentDAO();
                $studentDAO->removeAll();
                $data = fgetcsv($student);
                while(($data = fgetcsv($student)) != false){
                    #to start from the second line (with data)
                    #0 -> userid, 1 -> password, 2 -> name, 3 -> school, 4 -> edollar
                    
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) || !empty($data[1]) || !empty($data[2]) || !empty($data[3]) || !empty($data[4])){
                        $new_student = new Student($data[0], $data[1], $data[2], $data[3], $data[4]);
                        $studentDAO->add($new_student);
                        $student_processed++;
                    }
                }

            }
        }
    }  
    
    
#returning JSON format errors. remember this is only for the JSON API. Humans should not get JSON errors.
if (!isEmpty($errors))
{	
    $sortclass = new Sort();
    $errors = $sortclass->sort_it($errors,"bootstrap");
    $result = [ 
        "status" => "error",
        "messages" => $errors
    ];
}

else
{	
    $result = [ 
        "status" => "success",
        "num-record-loaded" => [
            "student.csv" => $student_processed,
            "course.csv" => $course,
            "section.csv" => $section_processed
        ]
    ];
}
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);



}
?>