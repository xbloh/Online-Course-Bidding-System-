<!DOCTYPE html>
<html>
<head>
<style>
    .button {
        background-color: #1c87c9;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 20px;
        margin: 2px 2px;
        cursor: pointer; 
    }

    a:hover {
    background-color: #555;
    color: white;
    }

    h1 {
        font-size: 30px;
    }

    th {
        text-align: center;
    }
</style>
</head>

<header>
  <nav>
    <table><tr>
      <th><img class="logo" src="img/merlion_uni_logo.PNG" alt="" width="150" height="100"></th>
      <th><a href="welcome.php" class="button">Home</a></th>
      <th><a href ="bidPreProcessing.php" class="button">Add Bid</a></th>
      <th><a href ="updateBid.php" class="button">Update Bid</a></th>
      <th><a href ="deleteBid.php" class="button">Delete Bid</a></th>
      <th><a href ="dropSection.php" class="button">Drop Section</a></th>
      <th><a href ="logout.php" class="button">Logout</a></th>
    </tr></table>
  </nav>
  <label for="nav-toggle" class="nav-toggle-label">
    <span></span>
  </label>
</header>
</html>