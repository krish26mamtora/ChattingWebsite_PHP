<?php
$link = mysqli_connect("localhost", "root", "", "chattingapp");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$query1 = "TRUNCATE TABLE user_details";
$query2 = "TRUNCATE TABLE user_connections";
$query3 = "TRUNCATE TABLE user_profile";

if (mysqli_query($link, $query1) && mysqli_query($link, $query2) && mysqli_query($link, $query3)) {
    echo "Table  has been emptied successfully.";
} else {
    echo "Error emptying table: " . mysqli_error($link);
}

mysqli_close($link);
?>
