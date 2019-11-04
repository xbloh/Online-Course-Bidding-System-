<?php
require_once 'include/common.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Welcome to BIOS</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

</head>

<body class="text-center">
    <form class="form-signin" action="process.php" method="POST">
        <img class="mb-4" src="img/merlion_uni_logo.PNG" alt="" width="300" height="200">
        
        <h1 class="h3 mb-3 font-weight-normal">Login Page</h1>

        <?php
            // Check if there are any errors
            // Display error messages (if any)
            //Is there a session variable 'error'?
            printErrors();
                // echo "<p style='color: red'>$msg</p>";
            
        ?>
        
        <label for="inputEmail" class="sr-only">Username</label>
        <input type = 'text' name = 'userid' id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>

    </form>
</body>
</html>