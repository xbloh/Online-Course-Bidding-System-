<?php
include 'menu_admin.php'
?>

<html>
    <h2>Round 2 started</h2>
</html>
<?php
    require_once 'include/common.php';
    $roundDAO = new RoundDAO();
    $roundDAO->startRound2();

echo "<a href = 'admin.php'>go back</a>";
?>