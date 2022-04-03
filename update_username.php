<?php
session_start();
require_once "./connection.php";

// get the user_id
$id = $_SESSION['user_id'];
// get the username from AJAX
$newUsername = $_POST['newUsername'];
$newUsername = htmlspecialchars(trim($newUsername));
// variables
$ok = true;
$messages = array();

$query = "UPDATE users SET username = '$newUsername' WHERE user_id='$id' ";
$result = mysqli_query($conn, $query);

if (!$result) {
    $ok = false;
    $messages[] = "Error in processing your request now. Please try again.";

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