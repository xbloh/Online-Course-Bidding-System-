<html>
<?php

require_once 'include/common.php';

$student = $_SESSION['student'];
$name = $student->getName();
$eDollar = $student->getEdollar();

var_dump($_SESSION);

?>

Welcome to BOIS, <?php echo $name; ?>
<br>
You currently have <?php echo $eDollar; ?> eDollars
<br>
<a href ="../app/addBid.php">Click here to add bid</a>

</html>