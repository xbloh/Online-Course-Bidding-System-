<html>
<head>
<style>
    .button {
        background-color: #1c87c9;
        border: none;
        color: white;
        padding: 16px 30px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 20px;
        margin: 4px 2px;
        cursor: pointer; }

    h1 {
        font-size: 30px;
    }
</style>
</head>
<?php

require_once 'include/common.php';

$student = $_SESSION['student'];
$name = $student->getName();
$eDollar = $student->getEdollar();

$coursesCompletedDAO = new CoursesCompletedDAO();
$coursesCompleted = $coursesCompletedDAO->retrieveCoursesCompleted($student);
$student->setCoursesCompleted($coursesCompleted);

//var_dump($_SESSION);

?>
<body>
<h1>Welcome to BOIS, <?php echo $name; ?></h1>
<br>
You currently have <?php echo $eDollar; ?> eDollars
<br>
<a href ="bidPreProcessing.php" class="button">Click Here to Add Bid</a>
</body>
</html>