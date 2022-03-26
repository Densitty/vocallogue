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
  <title>Vocalogue | Password Reset</title>

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
    background-color: #32767e;
    border-color: #32767e;
    color: #000;
  }

  #btn {
    color: #fff;
    background-color: #32767e;
    border-color: #32767e;
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
          <li><a class="navlink" href="">Help</a></li>
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
        <h1>Reset Password</h1>

        <!-- error message -->
        <div style="font-size: 1.7rem" id="result_message"></div>
        <?php
// check for the user_id and the activation key
if (!isset($_GET["user_id"]) || !isset($_GET['key'])) {
    echo "<div class='alert alert-error'>There was an error. Please click on the link you received in your email.</div>";
    exit;
}
// else {
$user_id = $_GET['user_id'];
$key = $_GET['key'];
/* password reset code should be active for only 24 hours; compare present time with time in forgot_password table to check if 24hrs has not elapsed */
$time = time() - 24 * 60 * 60;

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$key = mysqli_real_escape_string($conn, $_GET['key']);

// run the query to check for the user_id & key and the status is pending
$query = "SELECT user_id FROM forgot_password WHERE (user_id='$user_id' AND security_key='$key' AND time > $time) AND status= 'pending' ";

$result = mysqli_query($conn, $query);
// print_r($result);

if (!$result) {
    echo '<div class="alert alert-danger">Error running the query.</div>';
    exit;
}

$count = mysqli_num_rows($result);
if ($count !== 1) {
    echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
    exit;
}

// if everything is okay and our query is successful, print reset password form with hidden user_id and key fields
echo "
<form id='password_reset' method='POST'>
  <input type='hidden' name='key' value=$key />

  <input type='hidden' name=user_id value=$user_id />

  <div class='form-group'>
    <label style='color:#b1f5f2' for='password'>Enter your new Password</label>
    <input type='password' name='password' id='password' placeholder='Enter your new password' class='form-control' />
  </div>

  <div class='form-group'>
    <label style='color:#b1f5f2' for='password'>Re-enter password</label>
    <input type='password' name='password' id='password2' placeholder='Re-enter password' class='form-control' />
  </div>

  <button type='submit' class='btn btn-success' name='resetpassword' value='reset password'>Reset Password</button>
</form>
";
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
  <!-- <script src="./assets/js/script.js"></script> -->
  <script>
  // make an ajax call to store_reset_password.php to process the form data;
  const password_reset = document.getElementById("password_reset");

  const result_message = document.getElementById("result_message");


  password_reset.addEventListener("submit", (e) => {
    e.preventDefault();

    const key = e.path[0][0];
    const user_id = e.path[0][1];
    const passwordReset = e.path[0][2];
    const passwordResetConfirm = e.path[0][3];


    const request = new XMLHttpRequest();

    request.addEventListener("load", () => {
      let response = null;

      if (request.readyState === 4 && request.status === 200) {

        let responseObject = null;

        // get response from the server
        responseObject = JSON.parse(request.responseText);

        if (responseObject) {
          if (responseObject.ok) {

            clearOutputMessage(result_message);

            displayResponseObjectMessage(
              responseObject,
              result_message,
              "#b1f5f2"
            );
          } else {
            clearOutputMessage(result_message);

            displayResponseObjectMessage(
              responseObject,
              result_message,
              "#fe0001"
            );

          }
        }
      } else {
        result_message.innerHTML = `
      <div class="alert alert-danger">An error occured. Please try again later.</div>
      `;
      }
    });

    // get the data from the form and send to server
    const dataToPost =
      `key=${key.value}&user_id=${user_id.value}&passwordReset=${passwordReset.value}&passwordResetConfirm=${passwordResetConfirm.value}`;

    request.open("POST", "./store_reset_password.php");

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    request.send(dataToPost);
  });

  function displayMessages(message, targetElement, color = "#2c0faa") {
    const li = document.createElement("li");
    li.innerHTML = message;

    // color the text differently
    li.style.color = color;
    li.style.marginBottom = "12px";

    targetElement.appendChild(li);
  }

  function clearOutputMessage(targetElement) {
    while (targetElement.firstChild) {
      targetElement.firstChild.remove();
    }
  }

  function displayResponseObjectMessage(responseObject, targetElement, color) {
    responseObject.messages.forEach((msg) => {
      displayMessages(msg, targetElement, color);
    });
  }
  </script>
</body>

</html>