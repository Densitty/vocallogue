<?php
// user gets redirected to this file after clicking on the activation link in the email
// start session
session_start();
// connect to the db;
require_once "./connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <title>Vocalogue | New Email Activation</title>

  <style>
  .row {
    width: 100%;
    height: 100vh;
    position: relative;
  }

  .row div {
    top: 15%;
    left: 0%;
  }

  .row div h1 {
    color: #fff;
    margin-bottom: 5rem;
  }

  .success {
    /* background-color: #32767e; */
    background-color: #00e7ff;
    border-color: #32767e;
    color: #000;
  }

  #success {
    color: #000;
    background-color: #dff0d8;
    font-size: 1.7rem;
    font-weight: 600;
  }

  #btn {
    color: #fff;
    background-color: #06121a;
    border-color: #06121a;
    cursor: pointer;
  }
  </style>
</head>



<body>
  <nav role="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a href="./index.php" class="navbar-brand" id="logo">Vocalog</a>
        <button class="navbar-toggle" data-target="#navbar-collapse" data-toggle="collapse">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a class="navlink" href="">Home</a></li>
          <!-- <li><a class="navlink" href="">Help</a></li> -->
          <li><a class="navlink" href="">Contact</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <!-- <li class=""><a href="#loginModal" data-toggle="modal">Login</a></li> -->
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-sm-offset-1 col-sm-10">
        <h1>Activate New Email</h1>

        <?php
// check email to find if email and key parameters are present
if (!isset($_GET["email"]) || !isset($_GET['key']) || !isset($_GET["newemail"])) {
    echo "<div class='alert alert-error'>There was an error. Please click on the activation link you received in your email.</div>";
} else {
    $current_email = mysqli_real_escape_string($conn, $_GET['email']);
    $new_email = mysqli_real_escape_string($conn, $_GET['newemail']);
    $key = mysqli_real_escape_string($conn, $_GET['key']);

    // run the query to update email
    $query = "UPDATE users SET activation_2='0', email='$new_email' WHERE (email='$current_email' AND activation_2='$key') LIMIT 1";

    $result = mysqli_query($conn, $query);
    print_r($result);
    // if query is successful, show success message
    if (mysqli_affected_rows($conn) === 1) {
        // destroy the session so as to log user out and also delete the rememberme cookie already associated with the old_email to prevent re-use of old email to access account
        session_destroy();
        setcookie("rememberme", "", time() - 3600);

        echo "<div class='alert alert-success' id='success'> You email has been updated. Please <a href='index.php' type='button' class='btn btn-success' id='btn'>Login</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Your email could not be updated. Please try again.</div>";
    }
}
?>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <p>
        &copysr; Peter Xuan' ang
        <?php
$today = date("Y");
echo $today;
?>
      </p>
    </div>
  </footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
</body>

</html>