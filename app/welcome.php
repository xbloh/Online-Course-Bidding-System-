<html>
<?php

session_start();

require_once 'include/common.php';

$userid = $_SESSION['userid'];



?>

Welcome to BOIS, <?php echo $userid; ?>
</html>