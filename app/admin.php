<?php

require_once 'include/protect_admin.php';
include 'menu_admin.php'
?>

<body>
<h1>Welcome to BIOS, Admin!</h1>


<h2><?php
date_default_timezone_set('Asia/Singapore');
$day = date('l');
$time = date('h:i:s A');
$date = date('j F Y');
echo "Today is ". $day. ", ".$date."!";    ?></h2>
<h3> <?php echo "The current time is ". $time;    ?></h3>


</body></html>
</body>
</html>

