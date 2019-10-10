<?php

require_once 'include/common.php';

if( isset($_POST['userid']) && isset($_POST['password']) )
{
    if ($_POST['userid'] === "admin") {
        if ($_POST['password'] === "@dm1n5PM") {
            header('Location: bootstrapinterface.php');
            exit;
        } else {
            $_SESSION['errors'][] = "wrong admin password!";
            header('Location: login.php');
            exit;
        }
    }
    $userid = $_POST['userid'];

    $_SESSION['userid']=$userid;
    header('Location: deleteBid.php');
    
    $password = $_POST['password'];

    $dao = new StudentDAO();
    $result = $dao->authenticate($userid, $password);
    $return_message = $result[0];

    if($return_message == 'success') 
    {
        $_SESSION['student'] = $result[1];
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


