<?php
// start session and connect to the db
session_start();
require_once "./connection.php";

$user_id = $_POST['user_id'];
$key = $_POST['key'];
$password1 = isset($_POST['passwordReset']) ? $_POST['passwordReset'] : "";
$password2 = isset($_POST['passwordResetConfirm']) ? $_POST['passwordResetConfirm'] : "";

// responses
$ok = true;
$messages = array();
// // error messages
$missing_password = "Please enter your password!";
$missing_password_confirm = "Please confirm your password!";
$password_not_matched = "Your password fields should match.";
$invalid_password = "Your password should be at least 6 characters and must include a capital letter and one number!";

// // set the new password
if (empty($password1)) {
    $ok = false;
    $messages[] = $missing_password;
} elseif (!(strlen($password1) > 6 && preg_match("/[A-Z]/", $password1) && preg_match("/[0-9]/", $password1))) {
    $ok = false;
    $messages[] = $invalid_password;
} else {
    $password = mysqli_real_escape_string($conn, $password1);

    // check for the confirm password
    if (empty($password2)) {
        $ok = false;
        $messages[] = $missing_password_confirm;
    } else {
        $password2 = mysqli_real_escape_string($conn, $password2);
        // check if both passwords match
        if ($password1 !== $password2) {
            $ok = false;
            $messages[] = $password_not_matched;
        }
    }
}

// print_r($_POST);

if (!isset($_POST["user_id"]) || !isset($_POST['key'])) {
    echo "<div class='alert alert-error'>There was an error. Please click on the link you received in your email.</div>";
    exit;
}
// else {

/* password reset code should be active for only 24 hours; compare present time with time in forgot_password table to check if 24hrs has not elapsed */
$time = time() - 24 * 60 * 60;

$user_id = mysqli_real_escape_string($conn, $user_id);
$key = mysqli_real_escape_string($conn, $key);

// run the query to check for the user_id & key
$query = "SELECT user_id FROM forgot_password WHERE (user_id='$user_id' AND security_key='$key' AND time > $time) AND status = 'pending' ";

$result = mysqli_query($conn, $query);
// print_r($result);

if (!$result) {
    $ok = false;
    $messages[] = '<div class="alert alert-danger">Error running the query.</div>';
    exit;
}

$count = mysqli_num_rows($result);
if ($count !== 1) {
    $ok = false;
    $messages[] = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
    exit;
}

// hash the password before saving into db
$password1 = hash("sha256", $password1);
// run query to update the user password
$query = "UPDATE users SET password = '$password1' WHERE user_id = '$user_id' ";
$result = mysqli_query($conn, $query);

if (!$result) {
    $ok = false;
    $messages[] = "There was a problem storing the new password.";
    exit;
}

if (!$result) {
    $ok = false;
    $messages[] = "Error running the query.";
    exit;
}

if ($ok) {
    // if the password is successfully changed, disable the activation key in the forgot password from being used again
    $query_forgot_password = "UPDATE forgot_password SET status = 'used' WHERE (security_key = '$key' AND user_id = '$user_id' )";
    $result = mysqli_query($conn, $query_forgot_password);

    $messages[] = "Your password has been updated successfully. Kindly login with your new password <a href='index.php'>Login</a>";
}

// $messages[] = "You are welcome here";
echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);