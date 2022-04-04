<?php
session_start();
require_once "./connection.php";

// get the id
$id = $_SESSION['user_id'];

$missing_current_password = "Please enter your password!";
$incorrect_current_password = "The password entered is not correct.";
$missing_new_password = "You must give the password you want to change to.";
$missing_new_password_confirm = "Please confirm your password!";
$password_not_matched = "Your password fields should match.";
$invalid_password = "Your password should be at least 6 characters and must include a capital letter and one number!";
$ok = true;
$messages = array();

$current_password = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : "";
$new_password = isset($_POST["newPassword"]) ? $_POST["newPassword"] : "";
$new_password_confirm = isset($_POST["confirmNewPassword"]) ? $_POST["confirmNewPassword"] : "";

if (empty($current_password)) {
    $ok = false;
    $messages[] = $missing_current_password;
    $messages[] = $missing_new_password;
} else {
    $current_password = mysqli_real_escape_string($conn, $current_password);
    // hash the password
    $current_password = hash('sha256', $current_password);

    // check if the password is correct
    $query = "SELECT password FROM users WHERE user_id='$id' ";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);

    if ($count !== 1) {
        $ok = false;
        $messages[] = "There was a problem running the query.";
    } else {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // check if the password entered and the one in the db matches
        if ($current_password !== $row['password']) {
            $ok = false;
            $messages[] = $incorrect_current_password;
        }
    }
}

if (!(strlen($new_password) > 6 && preg_match("/[A-Z]/", $new_password) && preg_match("/[0-9]/", $new_password))) {
    $ok = false;
    $messages[] = $invalid_password;
} else {
    $new_password = mysqli_real_escape_string($conn, $new_password);

// check for the new password confirm field
    if (empty($new_password_confirm)) {
        $ok = false;
        $messages[] = $missing_new_password_confirm;
        exit;
    } else {
        $new_password_confirm = mysqli_real_escape_string($conn, $new_password_confirm);
        // check if both passwords match
        if ($new_password !== $new_password_confirm) {
            $ok = false;
            $messages[] = $password_not_matched;
            exit;
        }
    }

}

$new_password = hash('sha256', $new_password);
$query_password = "UPDATE users SET password='$new_password' WHERE user_id='$id' ";
$result_password = mysqli_query($conn, $query_password);
// print_r($result_password);
if (!$result_password) {
    $ok = false;
    $messages[] = "Password could not be reset at this time. Please try again later.";

    die(json_encode(
        array(
            "ok" => false,
            "messages" => $messages,
        )
    ));
}

if ($ok) {
    $messages[] = "Password successfully updated.";

}
echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);
/*
elseif (!(strlen($password) > 6 && preg_match("/[A-Z]/", $password) && preg_match("/[0-9]/", $password))) {
$ok = false;
$messages[] = $invalid_password;
} else {
$password = mysqli_real_escape_string($conn, $password);

// check for the confirm password
if (empty($password_confirm)) {
$ok = false;
$messages[] = $missing_password_confirm;
} else {
$password_confirm = mysqli_real_escape_string($conn, $password_confirm);
// check if both passwords match
if ($password !== $password_confirm) {
$ok = false;
$messages[] = $password_not_matched;
}
}
}
 */