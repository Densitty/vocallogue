<?php
// connect to the db
$conn = mysqli_connect("127.0.0.1:3308", "root", "", "vocalogue") or die("Connection couldn't be established. Please try again.");

// if ($conn) {
//     echo "We are connected";
// } else {
//     die("Connection couldn't be establsihed. Please try again.");
// }