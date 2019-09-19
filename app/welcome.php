<html>
<?php

require_once 'include/common.php';

$userid = $_SESSION['userid'];

var_dump($_SESSION);



?>

Welcome to BOIS, <?php echo $userid; ?>
</html>