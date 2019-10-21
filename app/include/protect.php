<?php

require_once('common.php');

 // No session variable "student" => no login
 if ( !isset($_SESSION["student"]) ) {

    // add to error message
    $_SESSION['errors'][]='Please login!';
    
    // redirect to login page
   header('Location: login.php');
   
    
    // stop all further execution
    // (if there are statements below)
    exit;
}

?>