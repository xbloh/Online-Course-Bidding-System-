<?php
require_once 'include/common.php';

$userId=$_SESSION['student']->getUserId();
// $section='S1';
// $course='IS102';
$bidDAO= new BidDAO();
$studentDAO = new StudentDAO();
$sectionDAO = new SectionDAO();

$roundDAO = new RoundDAO();
$currentRnd = $roundDAO->retrieveCurrentRound();
$rndStatus = $roundDAO->retrieveRoundStatus();

// $bidDAO->deleteBid($userId, $course, $section);
// var_dump($_POST['deleteCourseSection']);
if(isset($_POST['deleteCourseSection']))
{
    // foreach($_POST['deleteCourseSection'] as $check){
    $CourseSection=explode('+', $_POST['deleteCourseSection']);
    $amount = $bidDAO->retrieveBiddedAmt($userId, $CourseSection[0], $CourseSection[1]);
    $studentDAO->addEdollar($userId, $amount);
    $deleteStatus=$bidDAO->deleteBid($userId, $CourseSection[0], $CourseSection[1]);
    $vacancy = $sectionDAO->retrieveSectionSize($CourseSection[0], $CourseSection[1]);
    
    if($currentRnd == '2' && $rndStatus == 'active')
		{
			$winList = $bidDAO->winBids($CourseSection[0], $CourseSection[1], $vacancy, 2);
			$allRoundTwo = $bidDAO->retrieveAllByCourseSection($CourseSection[0], $CourseSection[1], 2);
            for ($index=0; $index < count($allRoundTwo); $index++) 
            {
                if(!in_array($index, $winList))
                {
                    $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'out');
                } 
                else
                {
                    $bidDAO->updateStatus($allRoundTwo[$index][0], $allRoundTwo[$index][1], $allRoundTwo[$index][2], 'in');
                }
            }
		}
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