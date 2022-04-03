<?php
// start session
session_start();
// check if user is not logged and and redirect to index on any attempt to access page
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
}
// retrieve username from users table in order to update the username after updating it
require_once "./connection.php";

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users where user_id = '$user_id' ";
$results = mysqli_query($conn, $query);
// get the results array returned back
$count = mysqli_num_rows($results);

if ($count === 1) {
    $row = mysqli_fetch_array($results, MYSQLI_ASSOC);

    $username = $row['username'];
    $email = $row['email'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/mainpage.css">
  <title>Profile | Learn and Log</title>
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
          <!-- <li class=""><a class="navlink" href="">Home</a></li> -->
          <li class="active"><a class="navlink" href="./profile.php">Profile</a></li>
          <li><a class="navlink" href="">Help</a></li>
          <li><a class="navlink" href="#">Contact</a></li>
          <li class=""><a class="navlink" href="./mainpage.php">My Word Logs</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class=""><a>Hello, <b><?php echo $username; ?></b></a>
          </li>
          <li class=""><a href="/index.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="container profile">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <h2>Profile Settings</h2>
        <div class="">
          <table class="table table-responsive">

            <tr data-target="#update_username" data-toggle="modal">
              <td>Username</td>
              <td><?=$username;?></td>
            </tr>
            <tr data-target="#update_email" data-toggle="modal">
              <td>Email</td>
              <td><?=$email;?>

              </td>
            </tr>
            <tr data-target="#update_password" data-toggle="modal">
              <td>Password</td>
              <td>*****</td>
            </tr>

          </table>
        </div>
      </div>
    </div>
  </section>

  <!--Update Username-->
  <form method="post" id="update_username_form">
    <div class="modal" id="update_username" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Enter New Username
            </h4>
          </div>

          <div class="modal-body">

            <!--Login message from PHP file-->
            <div id="username_change"></div>


            <div class="form-group">
              <!-- <label for="username" class="">Your Username</label> -->
              <input class="form-control" type="text" placeholder="Enter the new username you want to change to here"
                name="update_username" id="username" maxlength="30" value="<?=$username;?>">
            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-toggle="modal">
              Submit
            </button>

            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Update Email form-->
  <form method="post" id="update_email_form">
    <div class="modal" id="update_email" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Enter New Email
            </h4>
          </div>

          <div class="modal-body">

            <!--Login message from PHP file-->
            <div id="email_change"></div>


            <div class="form-group">
              <!-- <label for="username" class="">New Email</label> -->
              <input class="form-control" type="email" name="update_email" id="email" value="<?=$email;?>">
            </div>

          </div>

          <div class="modal-footer">
            <input class="btn " name="change_email" type="submit" value="Submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Update password form-->
  <form method="post" id="update_password_form">
    <div class="modal" id="update_password" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Change Your Password
            </h4>
          </div>
          <div class="modal-body">

            <!--forgot password message from PHP file-->
            <div id="forgotpasswordmessage"></div>


            <div class="form-group">
              <!-- <label for="change_password" class="">Password</label> -->
              <input class="form-control input" type="password" name="currentpassword" id="currentpassword"
                placeholder="Your current password" maxlength="50" />

              <input class="form-control input" type="password" name="newpassword" id="newpassword"
                placeholder="Your new password" maxlength="50" />

              <input class="form-control input" type="password" name="confirmpassword" id="comfirmnewpassword"
                placeholder="Confirm password" maxlength="50" />
            </div>
          </div>

          <div class="modal-footer">
            <input class="btn green" name="change_password" type="submit" value="Submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="./assets/js/bootstrap.min.js"></script>
  <script src="./assets/js/profile.js"></script>
</body>

</html>