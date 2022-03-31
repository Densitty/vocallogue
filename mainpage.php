<?php
// start session
session_start();
// check if user is not logged and and redirect to index on any attempt to access page
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
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
  <title>Logs | Learn and Log</title>
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
          <li class=""><a class="navlink" href="./profile.php">Profile</a></li>
          <li><a class="navlink" href="">Help</a></li>
          <li><a class="navlink" href="">Contact</a></li>
          <li class="active"><a class="navlink" href="./mainpage.php">My Word Logs</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <li class=""><a href="#loginModal" data-toggle="modal">Logged in as <b>KBuri Kuku</b></a></li>
          <li class=""><a href="./index.php? logout=1">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="container mainpage">
    <!-- alert message -->
    <!-- <div class="alert alert-danger collapse" id="alert">
      <a class="close" data-dismiss="alert" href="">&times;</a>
      <p id="alert_content"></p>
    </div> -->

    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="buttons display_btns show_btns">
          <button type="button" id="addWord" class="btn add-btn">
            Add Word
          </button>

          <button type="button" id="edit" class="btn btn-info add-btn pull-right">
            Edit
          </button>




        </div>

        <div class="buttons edit_btns hide_btns">
          <button type="button" id="all-words" class="btn add-btn">
            All Words
          </button>

          <button type="button" id="done" class="btn add-btn pull-right">
            Done
          </button>
        </div>

        <div id="wordpad" class="word-pad">
          <input type="text" class="form-control" id="word_text">
          <div class="word">
            <h2></h2>
          </div>
          <textarea name="examples" id="word_notes" class="examples " cols="30" rows="10"
            placeholder="You can enter the definition or examples or notes on how the word you entered is used..."></textarea>
        </div>

        <div id="words" class="words">
          <!-- request to retrieve data from database using php -->
        </div>
      </div>
    </div>
  </section>

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
  <script src="./assets/js/words.js"></script>
</body>

</html>