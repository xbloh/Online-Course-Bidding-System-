<?php
require_once 'include/common.php';
require_once 'include/protect_admin.php';
include 'menu_admin.php';
?>

<form id='bootstrap-form' action="bootstrap-process.php" method="post" enctype="multipart/form-data">
	Bootstrap file: 
	<input id='bootstrap-file' type="file" name="bootstrap-file"></br>
	<input type="submit" name="submit" value="Import">
	<input type="hidden" name="ui" value="true">
</form>

