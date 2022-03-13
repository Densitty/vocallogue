<?php
// start session
session_start();
// connect to the db
require_once "./connection.php";

// error messages
$missing_email = "Please enter your email address!";
$missing_password = "Please enter your password!";

$errors = array();

$ok = true;
$messages = array();

// submitted fields
$email = isset($_POST['email']) ? $_POST['email'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if (empty($email)) {
    $ok = false;
    $messages[] = $missing_email;
}
// else {
//     // $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

//     // $email = mysqli_real_escape_string($conn, $email);
//     $email = htmlspecialchars($email, ENT_QUOTES);

// }

if (empty($password)) {
    $ok = false;
    $messages[] = $missing_password;
}
// else {
//     $password = htmlspecialchars($password, ENT_QUOTES);

// }

if ($ok) {
    $email = htmlspecialchars($email, ENT_QUOTES);
    $password = htmlspecialchars($password, ENT_QUOTES);

    // $ok = true;
    // $messages[] = "<p>Login successful</p>";

    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $password = hash('sha256', $password);

    // run a query to check if the email and password are registered
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated' ";
    $result = mysqli_query($conn, $query);

    $count = mysqli_num_rows($result);
    if ($count === 1) {
        // $row = mysqli_fetch_array()
        $row = mysqli_fetch_assoc($result);

        // print_r($row);
        // save the user details in a session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];

        if (empty($_POST['rememberme'])) {
            // echo "Success";
        } else {}

    } else {
        $ok = false;
        $messages[] = "Wrong username or password. Enter your correct login details to login.";

        // die(json_encode(
        //     array(
        //         "ok" => $ok,
        //         "messages" => $messages,
        //     )
        // ));
    }

}

echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);