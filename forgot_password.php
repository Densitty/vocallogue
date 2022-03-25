<?php
// start session
session_start();
// connect to db
require_once "./connection.php";

// form field
$email = isset($_POST['forgotEmail']) ? $_POST['forgotEmail'] : "";

// error variables
$missing_email = "Please enter your email address!";
$invalid_email = "Please enter a valid email address!";
$messages = array();

$ok = true;

if (empty($email)) {
    $ok = false;
    $messages[] = $missing_email;
} else {
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ok = false;
        $messages[] = $invalid_email;
    }
}

if ($ok) {
    $email = mysqli_real_escape_string($conn, $email);

// check if the email exists in the db
    $query = "SELECT * FROM users WHERE email = '$email' ";
    $result = mysqli_query($conn, $query);

// print_r($email);

    $count = mysqli_num_rows($result);

    if ($count === 1) {
        // $messages[] = "Message will be sent to reset your password shortly";
        // get the user_id relating to the email address
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];
        // create a unique activation code
        $activation_key = bin2hex(openssl_random_pseudo_bytes(16));
        // insert user details and activation key into the forgotpassword table
        $time = time();
        $status = "pending";

        $query = "INSERT INTO forgot_password (`user_id`, `security_key`, `time`, `status`) VALUES('$user_id', '$activation_key', '$time', '$status')";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            $ok = false;
            $messages[] = "There was an error in processing your request at this time. Please try again later.";
        } else {
            $url = "http://localhost/vocalog";
// send mail to the user with the activation link
            $body = "Please click on the link below to reset your password:\n\n";

            $body .= "$url/reset_password.php?user_id=$user_id&key=$activation_key";

            if (mail($email, 'Reset your password', $body, "From:" . 'vocalogue@gmail.com')) {
                $ok = true;
                $messages[] = "An email has been sent to $email. Click on the link to reset your password.";
            }

        }

    } else {
        $ok = false;
        $messages[] = "The email you have entered does not exist. Please check again or register.";
    }

}

echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);