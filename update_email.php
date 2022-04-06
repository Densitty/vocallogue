<?php
session_start();
require_once "./connection.php";

// get the id
$id = $_SESSION['user_id'];

$new_email = isset($_POST['newEmail']) ? $_POST['newEmail'] : "";

$ok = true;
$messages = array();

if (empty($new_email)) {
    $ok = false;
    $messages[] = "Please provide the email you want to change to.";

    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}

$query_email_existence = "SELECT email FROM users WHERE email='$new_email' ";
$result = mysqli_query($conn, $query_email_existence);
$count = mysqli_num_rows($result);

if ($count > 0) {
    $ok = false;
    $messages[] = "This email has already been registered. Kindly enter a new email";

    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));

}

// get the current email of user
$current_email_query = "SELECT * FROM users WHERE user_id='$id' ";
$current_email_result = mysqli_query($conn, $current_email_query);
$count = mysqli_num_rows($current_email_result);

if ($count === 1) {
    $row = mysqli_fetch_assoc($current_email_result);

    $current_email = $row["email"];
} else {
    $ok = false;
    $messages[] = "There was an error retrieving the email. Please try again later.";
    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}
// create a unique activation code to confirm the user is the owner of the email (need to alter the table to provide for new activation column)
$activation_key = bin2Hex(openssl_random_pseudo_bytes(16));
// insert new activation into the users table
$query = "UPDATE users SET activation_2='$activation_key' WHERE user_id='$id' ";
$result = mysqli_query($conn, $query);
if (!$result) {
    print_r(mysqli_error($conn));
    $ok = false;
    $messages[] = "Error in updating your email at this moment. Please try again.";

    die(json_encode(
        array(
            "ok" => $ok,
            "messages" => $messages,
        )
    ));
}
// send email with link to activate new email
$url = "http://localhost/vocalog";
// send mail to the user with the activation link
$body = "Please click on the link below to confirm you initiated the change of email. If you did not initiate the change of email, kindly update your password.\r\n";
$body .= "$url/activate_new_email.php?email=" . urlencode($current_email) . "&newemail=" . urlencode($new_email) . "&key=$activation_key";
if (mail($new_email, 'Email Update Confirmation', $body, "From:" . 'no_reply_vocalogue@gmail.com')) {
    $ok = true;
    $messages[] = "A mail has been sent to $new_email. Click on the link to update your email address on Vocalog.";
}

echo json_encode(
    array(
        "ok" => $ok,
        "messages" => $messages,
    )
);