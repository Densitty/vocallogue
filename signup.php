<?php
// start the session
session_start();
// connect to the database
require_once "./connection.php";

// error variables
$errors = array();
$missing_username = "Please enter a username!";
$missing_email = "Please enter your email address!";
$invalid_email = "Please enter a valid email address!";
$missing_password = "Please enter your password!";
$missing_password_confirm = "Please confirm your password!";
$password_not_matched = "Your password fields should match.";
$invalid_password = "Your password should be at least 6 characters and must include a capital letter and one number!";

// form input fields
$username = isset($_POST["username"]) ? $_POST["username"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$password_confirm = isset($_POST["password2"]) ? $_POST["password2"] : "";

$ok = true;
$messages = array();

//

// validate the form input fields
if ( /* !isset($_POST["username"]) || */empty($_POST["username"])) {
    $ok = false;
    $messages[] = $missing_username;
} else {
    // $username = htmlspecialchars($username, ENT_QUOTES);
    $username = mysqli_real_escape_string($conn, $username);
}

if (empty($email)) {
    $ok = false;
    $messages[] = $missing_email;
} else {
    // $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $email = mysqli_real_escape_string($conn, $email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ok = false;
        $messages[] = $invalid_email;
    }
}

if (empty($password)) {
    $ok = false;
    $messages[] = $missing_password;
} elseif (!(strlen($password) > 6 && preg_match("/[A-Z]/", $password) && preg_match("/[0-9]/", $password))) {
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

// check if the username already exists in the database
$query_username = "SELECT * FROM users WHERE username = '$username' ";
// run the query
$username_result = mysqli_query($conn, $query_username);

if (!$username_result) {
    $ok = false;
    $messages[] = "Error running the query. Please try again later. " /*  . mysqli_error($conn) . "." */;

    die_on_error($ok, $messages);

} else {
    $results = mysqli_num_rows($username_result);

    if ($results) {
        $ok = false;
        $messages[] = "Username already registered. Please login.";

        die_on_error($ok, $messages);
    }
}

$query_email = "SELECT * FROM users WHERE email = '$email' ";
$email_result = mysqli_query($conn, $query_email);

if (!$email_result) {

    $ok = false;
    $messages[] = "Error running the query";
    // die(json_encode(
    //     array(
    //         "ok" => $ok,
    //         "messages" => $messages,
    //     )
    // ));

    die_on_error($ok, $messages);

} else {
    $results = mysqli_num_rows($email_result);

    if ($results) {
        $ok = false;
        $messages[] = "Email already registered. Please login.";

        die_on_error($ok, $messages);

    }
}
// if everything is okay, save the details into db
if ($ok) {
    // create a unique activation code (a 32 xters long in hex)
    $activation_key = bin2Hex(openssl_random_pseudo_bytes(16));

    // hash the password before saving into db
    $password = hash("sha256", $password);

    // insert data into database
    $query = "INSERT INTO users (`username`, `email`, `password`, `activation`) VALUES ('$username', '$email', '$password', '$activation_key')";

    $result = mysqli_query($conn, $query);

    // if we have no result
    if (!$result) {
        $ok = false;
        $messages[] = "There was an error processing your signup. Please try again later.";
        die_on_error($ok, $messages);
    } else {
        $url = "http://localhost/vocalog";
        // send mail to the user with the activation link
        $body = "Please click on the link below to activate your account:\r\n";
        $body .= "$url/activate.php?email=" . urlencode($email) . "&key=$activation_key";
        if (mail($email, 'Please confirm your Resgistration', $body, "From:" . 'vocalogue@gmail.com')) {
            $ok = true;
            $messages[] = "Thank you for registering. A confirmation email has been sent to $email. Click on the link to activate your account.";
        }
    }
}

// send the response back to the client
echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);

function die_on_error($ok, $messages)
{
    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}