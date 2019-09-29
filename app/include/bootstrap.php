<?php
require_once 'common.php';

function doBootstrap(){
    $errors = array();

    #need tmp_name (temporary name) to create for the file and stored inside apache temp folder for proper read address
    $zip_file = $_FILES["bootstrap-file"]["tmp_name"];

    #get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

	#keep track of number of lines successfully processed for each file
    $student_processed=0;
    $course_processed=0;
    $section_processed=0;
    $prerequisite_processed=0;
    $course_completed_processed=0;
    $bid_processed=0;
    

    #check file size
    if ($_FILES["bootstrap-file"]["size"] <= 0)
    $errors[] = "Input files not found";

    else {
        
        $zip = new ZipArchive;
        $res = $zip->open($zip_file);

        if ($res === TRUE) {
            $zip->extractTo($temp_dir);
            $zip->close();
            #to save space on the cloud
            
            #reference to the csv in temporary directory on system which consists of all the csv files
            $student_path = "$temp_dir/student.csv";
            $course_path = "$temp_dir/course.csv";
            $section_path = "$temp_dir/section.csv";
            $prerequisite_path = "$temp_dir/prerequisite.csv";
            $course_completed_path = "$temp_dir/course_completed.csv";
            $bid_path = "$temp_dir/bid.csv";

            #open the file and read it only
            $student = @fopen($student_path, "r");
            $course = @fopen($course_path, "r");
            $section = @fopen($section_path, "r");
            $prerequisite = @fopen($prerequisite_path, "r");
            $course_completed = @fopen($course_completed_path, "r");
            $bid = @fopen($bid_path, "r");
            #if either files is empty
        
            if (empty($student) || empty($course) || empty($section) || empty($prerequisite) || empty($course_completed) || empty($bid)){
                $errors[] = "Input files not found in zip";
                if(!empty($student)){
                    fclose($student);
                    @unlink($student_path);
                }   
                if(!empty($course)){
                    fclose($course);
                    @unlink($course_path);
                }
                if(!empty($section)){
                    fclose($section);
                    @unlink($section_path);
                }
                if (!empty($prerequisite)){
					fclose($prerequisite);
					@unlink($prerequisite_path);
                } 
                if (!empty($course_completed)) {
					fclose($course_completed);
					@unlink($course_completed_path);
                }
                if (!empty($bid)){
					fclose($bid);
					@unlink($bid_path);
				} 
            }

            #if all files not empty
            else{

                //mysql_query('SET foreign_key_checks = 0');

				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();
				#start processing
                #truncate current SQL tables
                $studentDAO = New StudentDAO();
                $studentDAO->removeAll();

                $courseDAO = new CourseDAO();
                $courseDAO->removeAll();

                $sectionDAO = new SectionDAO();
                $sectionDAO->removeAll();

                $prerequisiteDAO = new PrerequisiteDAO();
                $prerequisiteDAO->removeAll();

                
                $course_completedDAO = new CoursesCompletedDAO();
                $course_completedDAO->removeAll();

                $bidDAO = new BidDAO();
                $bidDAO->removeAll();

                $errors = [];
                // #then read each csv file line by line (remember to skip the header)
				// #$data = fgetcsv($file) gets you the next line of the CSV file which will be stored 
				// #in the array $data
				// #$data[0] is the first element in the csv row, $data[1] is the 2nd, ....
				$header = fgetcsv($student);
				#to remove the first line, because it rmbs which line it reads
                $file = "student.csv";
                $line = 1;
                while(($data = fgetcsv($student)) != false){
                    #to start from the second line (with data)
                    #0 -> userid, 1 -> password, 2 -> name, 3 -> school, 4 -> edollar
                    $message = [];
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4])){
                        $new_student = new Student($data[0], $data[1], $data[2], $data[3], $data[4]);

                        $currentErrors = $new_student->validate();

                        if (empty($currentErrors)) {
                            $studentDAO->add($new_student);
                            $student_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        

                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    
                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;
                }
                fclose($student);
                @unlink($student_path);
                
                $header = fgetcsv($course);
                $file = "course.csv";
                $line = 1;
                while(($data = fgetcsv($course)) != false){
                    $message = [];
                    #0 -> course, #1 -> school, #2 -> title, #3 -> description, #4 -> exam date, #5 -> exam start, #6 -> exam end
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4]) && !empty($data[5]) && !empty($data[6])){
                        $new_course = new Course($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                        $currentErrors = $new_course->validate();
                        if (empty($currentErrors)) {
                            $courseDAO->add($new_course);
                            $course_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        
                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;
                }
                fclose($course);
                @unlink($course_path);

				$header = fgetcsv($section);
                $file = 'section.csv';
				$line = 1;
                while(($data = fgetcsv($section)) != false){
                    $message = [];
                    #0 -> course, #1 -> section, #2 -> day, #3 -> start, #4 -> end, #5 -> instructor, #6 -> venue, #7 -> size
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4]) && !empty($data[5]) && !empty($data[6]) && !empty($data[7])){
                        $new_section = new Section($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);

                        $currentErrors = $new_section->validate();
                        if (empty($currentErrors)) {
                            $sectionDAO->add($new_section);
                            $section_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        

                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;
                }
                fclose($section);
                @unlink($section_path);

                // mysql_query('SET foreign_key_checks = 1');
                $header = fgetcsv($prerequisite);
				$file = 'prerequisite.csv';
                $line = 1;
                while(($data = fgetcsv($prerequisite)) != false){
                    $message = [];
                    #0 -> course, #1 -> section, #2 -> day, #3 -> start, #4 -> end, #5 -> instructor, #6 -> venue, #7 -> size
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) && !empty($data[1])){
                        $new_prerequisite = new Prerequisite($data[0], $data[1]);

                        $currentErrors = $new_prerequisite->validate();
                        if (empty($currentErrors)) {
                            $prerequisiteDAO->add($new_prerequisite);
                            $prerequisite_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        
                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;

                }
                fclose($prerequisite);
                @unlink($prerequisite_path);

                $header = fgetcsv($course_completed);
				$file = 'course_completed.csv';
                $line = 1;
                while(($data = fgetcsv($course_completed)) != false){
                    #0 -> course, #1 -> section, #2 -> day, #3 -> start, #4 -> end, #5 -> instructor, #6 -> venue, #7 -> size
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    $message = [];
                    if(!empty($data[0]) && !empty($data[1])){
                        $new_course_completed = new Course_Completed($data[0], $data[1]);

                        $currentErrors = $new_course_completed->validate();
                        if (empty($currentErrors)) {
                            $course_completedDAO->add($new_course_completed);
                            $course_completed_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        
                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;

                }
                fclose($course_completed);
                @unlink($course_completed_path);

                $header = fgetcsv($bid);
                $file = "bid.csv";
                $line = 1;
				
                while(($data = fgetcsv($bid)) != false){
                    $message = [];
                    #0 -> course, #1 -> section, #2 -> day, #3 -> start, #4 -> end, #5 -> instructor, #6 -> venue, #7 -> size
                    #NEED TO LOOK AT WIKI FOR THE ERRORS INSTRUCTIONS!!!
                    if(!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3])){
                        $new_bid = new Bid($data[0], $data[1], $data[2], $data[3]);

                        $currentErrors = $new_bid->validate();
                        if (empty($currentErrors)) {
                            $bidDAO->add($new_bid);
                            $bid_processed++;
                        } else {
                            foreach ($currentErrors as $error) {
                                array_push($message, $error);
                            }
                        }
                        
                    }else {
                        foreach ($data as $key => $value) {
                            if (empty($value)) {
                                $message[] = "blank " . $header[$key];
                                break;
                            }
                        }
                    }

                    if (!empty($message)) {
                        $errors[] = ["file" => $file, "line" => $line, "message" => $message];
                    }

                    $line++;
                }
                fclose($bid);
                @unlink($bid_path);
            }
        }
        else{
            $errors[] = "input files is not zip";
        }
    }  
    

#returning JSON format errors. remember this is only for the JSON API. Humans should not get JSON errors.
    if (!empty($errors))
    {	
        // $sortclass = new Sort();
        // $errors = $sortclass->sort_it($errors,"bootstrap");
        $result = [ 
            "status" => "error",
            "num-record-loaded" => [
                ["student.csv" => $student_processed],
                ["course.csv" => $course_processed],
                ["section.csv" => $section_processed],
                ["prerequisite.csv" => $prerequisite_processed],
                ["course_completed.csv" => $course_completed_processed],
                ["bid.csv" => $bid_processed]
            ],
            "error" => $errors
        ];
    }
    else
    {	
        $result = [ 
            "status" => "success",
            "num-record-loaded" => [
                ["student.csv" => $student_processed],
                ["course.csv" => $course_processed],
                ["section.csv" => $section_processed],
                ["prerequisite.csv" => $prerequisite_processed],
                ["course_completed.csv" => $course_completed_processed],
                ["bid.csv" => $bid_processed]
            ]
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);



}
?>