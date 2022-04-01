<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";
// variables
$ok = true;
$messages = array();
// variables from data got from FE
$id = $_POST['activeNoteId'];
$words = $_POST['wordText'];
$word_notes = $_POST['wordNotes'];

// print_r($_POST);

// run the query
$query = "UPDATE words_logs SET words='$words', word_notes='$word_notes', time = now() WHERE id='$id' ";
$result = mysqli_query($conn, $query);

if (!$result) {
    $ok = false;
    $messages[] = "An error occured. Please try again later.";
    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}

$messages[] = "Success";
echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);