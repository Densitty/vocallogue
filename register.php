<?php
/* start session */
session_start();
require_once "./connection.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// error messages
$missing_username = "<p>Please enter a username!</p>";
$missing_email = "<p>Please enter your email address!</p>";
$invalid_email = "<p>Please enter a valid email address!</p>";
$missing_password = "<p>Please enter your password!</p>";
$missing_password_confirm = "<p>Please confirm your password!</p>";
$password_not_matched = "<p>Your password fields should match.</p>";
$invalid_password = "<p>Your password should be at least 6 characters and must include a capital letter and one number!</p>";
$errors = array();
// submitted fields
$username;
$email;
$password;
$password2;

// get the inputs from the form
if (empty($_POST["username"])) {
    array_push($errors, $missing_username);
} else {
    $username = mysqli_real_escape_string($conn, $username);
}

if (empty($_POST["email"])) {
    // $errors .= $missing_email;
    array_push($errors, $missing_email);
} else {
    // $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $email = mysqli_real_escape_string($conn, $email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // $errors .= $invalid_email;
        array_push($errors, $invalid_email);
    }
}

if (empty($_POST["password"])) {
    // $errors .= $missing_password;
    array_push($errors, $missing_password);
} elseif (!strlen(($_POST['password'])) > 6 && preg_match("/[A-Z]/", $_POST["password"]) && preg_match("/[0-9/]", $_POST['password'])) {
    // $errors .= $invalid_password;
    array_push($errors, $invalid_password);
} else {
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // check for the confirm password
    if (empty($_POST["password2"])) {
        // $errors .= $missing_password_confirm;
        array_push($errors, $missing_password_confirm);
    } else {
        $password_confirm = mysqli_real_escape_string($conn, $_POST['password2']);
        // check if both passwords match
        if ($password !== $password_confirm) {
            // $errors .= $password_not_matched;
            array_push($errors, $password_not_matched);
        }
    }
}

// if there are errors
if ($errors) {
    $result_message = "<div class='alert alert-danger'>";
    foreach ($errors as $error) {
        $result_message .= $error;
    }
    $result_message .= "</div>";
    echo $result_message;

}