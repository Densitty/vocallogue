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

        print_r($_POST);
        if (empty($_POST['rememberme'])) {
            // echo "Success";

            $messages[] = "You are logged in but not remembered";
        } else {
            // create 2 variables
            // first variable in hex
            $authenticator1 = bin2hex(openssl_random_pseudo_bytes(10)); // will give 20 xters
            // second variable in bin
            $authenticator2 = openssl_random_pseudo_bytes(20);

            function saveCookieValue($auth1, $auth2)
            {
                return $auth1 . "," . bin2hex($auth2);
            }

            // store them in a cookie
            $cookieValue = saveCookieValue($authenticator1, $authenticator2);
            setcookie('rememberme',
                $cookieValue,
                time() + 15 * 24 * 60 * 60//15*24*60*60 (15 days in secs)
            );

            function f2auth($auth1)
            {
                return hash('sha256', $auth1);
            } // will produce 64 xters

            $f2authenticator2 = f2auth($authenticator1);

            // store the user_id as a session
            $user_id = $_SESSION['user_id'];

            // remember the password for 15 days
            $expiration = date("Y-m-d H:i:s", time() + 15 * 24 * 60 * 60);
            // run query to store the cookie value in the rememberme table in db
            $query = "INSERT INTO remember_me(`authenticator1`, `f2authenticator2`, `user_id`, `expires`) VALUES ('$authenticator1', '$f2authenticator2', '$user_id', '$expiration')";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                $ok = false;
                $messages[] = 'There was an error in processing the request. Please login again.';
            } else {
                $messages[] = 'Logged and password remembered for 15 days.';
            }
        }

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