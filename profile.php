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
          <li><a class="navlink" href="">Contact</a></li>
          <li class=""><a class="navlink" href="./mainpage.php">My Word Logs</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class=""><a href="#loginModal" data-toggle="modal">Logged in as <b>KBuri Kuku</b></a></li>
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
              <td>Kburi Kuku</td>
            </tr>
            <tr data-target="#update_email" data-toggle="modal">
              <td>Email</td>
              <td>kburikuku@gmail.com
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
            <!-- <div id="loginmessage"></div> -->


            <div class="form-group">
              <!-- <label for="username" class="">Your Username</label> -->
              <input class="form-control" type="text" name="update_username" id="username" value="" maxlength="30">
            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal" data-target="#signupModal"
              data-toggle="modal">
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

  <!--Sign up form-->
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
            <!-- <div id="loginmessage"></div> -->


            <div class="form-group">
              <!-- <label for="username" class="">New Email</label> -->
              <input class="form-control" type="email" name="update_email" id="email" value="">
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
              <input class="form-control input" type="password" name="currentpassword" id="forgotemail"
                placeholder="Your current password" maxlength="50" />

              <input class="form-control input" type="password" name="newpassword" id="forgotemail"
                placeholder="Your new password" maxlength="50" />

              <input class="form-control input" type="password" name="confirmpassword" id="forgotemail"
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
  <script src="javascript.js"></script>
</body>

</html>