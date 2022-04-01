<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";

// variables
$ok = true;
$messages = array();

// // get the user id from the session variable
$user_id = $_SESSION['user_id'];
// $username = $_SESSION['username'];

// // run a query to delete empty entries
$query = "DELETE FROM words_logs WHERE words = '' ";
$result = mysqli_query($conn, $query);

if (!$result) {
    $ok = false;
    $messages[] = "An error occured";
    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}

$query_words = "SELECT * FROM words_logs WHERE user_id = '$user_id' ORDER BY time ASC";
$result2 = mysqli_query($conn, $query_words);
// print_r(mysqli_num_rows($result2));
$words_result = array();
if ($result2) {
    if (mysqli_num_rows($result2) > 0) {
        while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
            $id = $row['id'];
            $word = $row['words'];
            $notes = $row['word_notes'];
            $time = $row['time'];
            $time = date("F d, Y h:i:s A", strtotime($time));
            $words = array(
                "id" => $id,
                "word" => $word,
                "notes" => $notes,
                "time" => $time,
            );

            // push each array of objs in
            array_push($words_result, $words);

        }

        $messages[] = $words_result;

    } else {
        $messages[] = "You have not created any word logs yet!";
    }
    $ok = true;

} else {
    $ok = false;
    // $messages[] = "An error occured. Please try again.";
    $messages[] = "Unable to execute query:  $query_words." . mysqli_error($conn);

    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}

echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);