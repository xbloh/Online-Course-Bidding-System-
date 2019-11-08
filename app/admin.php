<?php

require_once 'include/protect_admin.php';
include 'menu_admin.php'
?>

<body>
<h1>Welcome to BIOS, Admin!</h1>


<h2><?php    $day = date('l'); $date = date('j F Y');    echo "Today is ".$day. ", ".$date."!";    ?></h2>
<?php #tried to add this but not sure why the time seems to be in the wrong timezone ?>
<!-- <h2><?php    $time = date('g:i:s');    echo "The time is now ".$time;    ?></h2> -->
</body></html>
</body>
</html>