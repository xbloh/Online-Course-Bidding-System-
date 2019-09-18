<?php

require_once 'include/common.php';

if( isset($_POST['userid']) && isset($_POST['password']) )
{
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $dao = new UserDAO();
    $return_message = $dao->authenticate($userid, $password);

    if($return_message == 'SUCCESS') 
    {
        $_SESSION['userid'] = $userid;
        header('Location: welcome.php');
    }
    elseif ($return_message == 'Incorrect Password!') {
        $_SESSION['errors'][]=$return_message;
        header('Location: login.php');
    }
    else
    {
        $_SESSION['errors'][]=$return_message;
        header('Location: login.php');
    }
}
else
{
    $_SESSION['errors'][]="Please fill in userID and password";

}


