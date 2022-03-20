<?php
session_start();
require_once "./connection.php";

// logout
require_once "./logout.php";

// remember the user when rememberme is checked
require_once "./remember_me.php";
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
  <title>Vocalog | Learn and Log</title>
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
          <li class=""><a href="#loginModal" data-toggle="modal">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section id="my-container">
    <div class="jumbotron">
      <h2>build your vocabulary</h2>
      <p>Improve your word skills by logging new words you encounter each day. </p>
      <p>Access your word compendium anywhere, anytime, anyday</p>

      <button class="btn my-btn" data-target="#signupModal" data-toggle="modal">Sign Up</button>
    </div>
  </section>

  <!--Login form-->
  <form method="post" id="loginform">
    <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Login:
            </h4>
          </div>
          <div class="modal-body">

            <!--Login message from PHP file-->
            <div id="loginmessage"></div>


            <div class="form-group">
              <label for="loginemail" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="loginemail" id="loginemail" placeholder="Email"
                maxlength="50">
            </div>
            <div class="form-group">
              <label for="loginpassword" class="sr-only">Password</label>
              <input class="form-control" type="password" name="loginpassword" id="loginpassword" placeholder="Password"
                maxlength="30">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="rememberme" id="rememberme">
                Remember me
              </label>
              <a class="pull-right" style="cursor: pointer" data-dismiss="modal" data-target="#forgotpasswordModal"
                data-toggle="modal">
                Forgot Password?
              </a>
            </div>

          </div>
          <div class="modal-footer">
            <input class="btn green" name="login" type="submit" value="Login">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="#signupModal"
              data-toggle="modal">
              Register
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Sign up form-->
  <form method="POST" id="signupform">
    <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Register and build your vocabulary
            </h4>
          </div>
          <div class="modal-body">

            <!--Sign up message from PHP file-->
            <div id="signupmessage" class="signupmessage">
              <!--  -->
            </div>

            <div class="form-group">
              <label for="username" class="sr-only">Username:</label>
              <input class="form-control" type="text" name="username" id="username" placeholder="Username"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="email" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="email" id="email" placeholder="Email Address"
                maxlength="50">
            </div>
            <div class="form-group">
              <label for="password" class="sr-only">Choose a password:</label>
              <input class="form-control" type="password" name="password" id="password" placeholder="Choose a password"
                maxlength="30">
            </div>
            <div class="form-group">
              <label for="password2" class="sr-only">Confirm password</label>
              <input class="form-control" type="password" name="password2" id="password2" placeholder="Confirm password"
                maxlength="30">
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn" name="submit" type="submit" value="Sign up">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Forgot password form-->
  <form method="post" id="forgotpasswordform">
    <div class="modal" id="forgotpasswordModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">
              &times;
            </button>
            <h4 id="myModalLabel">
              Forgot Password? Enter your email address:
            </h4>
          </div>
          <div class="modal-body">

            <!--forgot password message from PHP file-->
            <div id="forgotpasswordmessage"></div>


            <div class="form-group">
              <label for="forgotemail" class="sr-only">Email:</label>
              <input class="form-control" type="email" name="forgotemail" id="forgotemail" placeholder="Email"
                maxlength="50">
            </div>
          </div>
          <div class="modal-footer">
            <input class="btn green" name="forgotpassword" type="submit" value="Submit">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-target="signupModal"
              data-toggle="modal">
              Register
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
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="./assets/js/script.js"></script>
</body>

</html>