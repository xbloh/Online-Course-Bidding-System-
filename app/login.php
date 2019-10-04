<?php

require_once 'include/common.php';

?>

<html>
<head>
<title>Welcome to BIOS</title>
<style>
h1 {
    font-size : 30px;
}
</style>
</head>

<body>

<form action = 'process.php' method = 'POST'>
Username/Email Address: <input type = 'text' name = 'userid'>
<br>
<br>
Password: <input type = 'password' name = 'password'>
<br>
<input type = 'submit' name = 'login' value = 'Login'>
</form>

</body>
</html>
<?php

printErrors();

?>