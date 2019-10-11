<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

session_start();

function printErrors() {
    if(isset($_SESSION['errors'])){
        echo "<ul'>";
        
        foreach ($_SESSION['errors'] as $value) {
            echo "<li >" . $value . "</li>";
        }
        
        echo "</ul>";   
        unset($_SESSION['errors']);
    }    
}

function isEmpty($var) {
    if (isset($var) && is_array($var))
        foreach ($var as $key => $value) {
            if (empty($value)) {
               unset($var[$key]);
            }
        }

    if (empty($var))
        return TRUE;
}

function isMissingOrEmpty($name) {
    if (!isset($_REQUEST[$name])) {
        return "$name is missing";
    }

    // client did send the value over
    $value = $_REQUEST[$name];
    if (empty($value)) {
        return "$name cannot be empty";
    }
}

?>