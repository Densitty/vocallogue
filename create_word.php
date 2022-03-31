<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";
// get the user id
$user_id = $_SESSION['user_id'];

$ok = true;
$messages = array();
// run query to create a new note
$query = "INSERT INTO words_logs (`user_id`,`words`,`word_notes`,`time`) VALUES ($user_id, '', '', now())";
$result = mysqli_query($conn, $query);

if ($result) {
// return the auto generated id of last request
    $messages[] = mysqli_insert_id($conn);
// print_r(mysqli_insert_id($conn));
    echo json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        ));

} else {
    $ok = false;
    // $messages[] = "Error occured. Please try again.";
    $messages[] = "Unable to execute query:  $query. " . mysqli_error($conn);
    echo (json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));

}

/*
<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";
// get the user id
$user_id = $_SESSION['user_id'];
// get the current time
$time = date("F d, Y h:i:s A");

$ok = true;
$messages = array();
// run query to create a new note
$query = "INSERT INTO words_logs (`user_id`,`words`,`word_notes`,`time`) VALUES ($user_id, '', '', $time)";
$result = mysqli_query($conn, $query);

if (!$result) {
$ok = false;
$messages[] = "Error";
die(json_encode(
array(
"ok" => $ok,
"messages" => $messages,
)
));

} else {
// return the auto generated id of last request
$messages[] = mysqli_insert_id($conn);
json_encode(
array(
"ok" => $ok,
"messages" => $messages,
));
}
 */