<?php

require_once 'include/common.php';

?>

<html>
<head>Welcome to BIOS</head>
<body>

Sign in with your SMU user ID or SMU Email address.<br>
e.g.<br>
Staff: smustf\marylim<br>
Student/Alumni: smustu\john.2014<br>
or<br>
Staff: marylim@smu.edu.sg<br>
Student/Alumni: john.2014@business.smu.edu.sg<br>
<br>

<form action = 'process.php' method = 'POST'>
Email Adress: <input type = 'text' name = 'email'>
<br>
Password: <input type = 'password' name = 'password'>
<br>
<input type = 'submit' name = 'login' value = 'Login'>
</form>

</body>
</html>