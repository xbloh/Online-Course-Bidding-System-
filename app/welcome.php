<html>
<?php

require_once 'include/common.php';

$student = $_SESSION['student'];
$userid = $student->getUserId();

var_dump($_SESSION);


?>

Welcome to BOIS, <?php echo $userid; ?>
</html>