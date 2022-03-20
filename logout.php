<?php
// check if the user is logged in and log out by destroying the session & deleting the cookie set
if (isset($_SESSION['user_id']) and (int) $_GET['logout'] === 1) {
    // destroy the session
    session_destroy();
    // delete the cookie too
    setcookie('rememberme', "", time() - 3600);
}