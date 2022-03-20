<?php
// check if the user through the user_id is not logged in and the rememberme cookie exists on the user client
if (!isset($_SESSION['user_id']) && !empty($_COOKIE['rememberme'])) {
    // f1: $auth1 . "," . bin2hex($auth2);
    //  f2: hash('sha256', $auth1);

    // extract authenticator1 & f2authenticator2 from the cookie stored on client, by dividing cookie value into 2 bits through the "," separator, using explode() & save into list() [sth similar to destructuring]
    list($auth1, $auth2) = explode(",", $_COOKIE['rememberme']);
    // $auth2 is stored in cookie as an hex value, convert back to bin
    $auth2 = hex2bin($auth2);

    // go to the remember me table and match the values for both authenticator1 & authenticator2
    $f2auth2 = hash('sha256', $auth2);

    // look for authenticator1 in rememberme table
    $query = "SELECT * FROM rememberme WHERE authenticator1 = `$auth1` ";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo '<div class="alert alert-danger">There was an error running your query.</div>';
        exit;
    }

    $count = mysqli_num_rows($result);

    if ($count !== 1) {
        echo '<div class="alert alert-danger">Remember me process failed!</div>';
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    // compare $f2auth2 with f2authenticator2 from rememberme table

    if (!hash_equals($row['f2authenticator2'], $f2auth2)) {
        echo '<div class="alert alert-danger">hash_equals returned false.</div>';
    } else {
        // generate a new authenticators and store them in cookie and rememberme table
        $authenticator1 = bin2hex(openssl_random_pseudo_bytes(10)); // will give 20 xters
        // second variable in bin
        $authenticator2 = openssl_random_pseudo_bytes(20);

        function saveCookieValue($auth1, $auth2)
        {
            return $auth1 . "," . bin2hex($auth2);
        }

        // store the created variables in a (rememberme) cookie
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
        }

        // login user and redirect to mainpage
        $_SESSION['user_id'] = $row['user_id'];

        header("location: ./mainpage.php");

    }

}
/*  else {
echo '<div style="margin-bottom: 10px; margin-top: 50px" class="alert alert-danger">
User_id: ' . $_SESSION['user_id'] .
'</div>';
echo '<div class="alert alert-danger">
Cookie value: ' . $_COOKIE['rememberme'] .
'</div>';
} */