<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";

// variables
$ok = true;
$messages = array();
$user_id = $_SESSION['user_id'];
$id = $_POST['activeNoteId'];

$query = "DELETE FROM words_logs WHERE id = '$id' AND user_id = '$user_id' ";
$result = mysqli_query($conn, $query);

if (!$result) {
    $messages[] = "Error in deleting your word entry. Please try again.";
    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}

$messages[] = "Word successfully removed from your log";

echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);