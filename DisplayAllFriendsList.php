<?php
echo '<h2>Your friends </h2><br>';
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    die("Connection file not found.");
}

$email = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
} else {
    die("Could not retrieve UID for the current user.");
}

$UIDofFriends = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
$result = mysqli_query($link, $UIDofFriends);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $Friends = $row['Friends'];

    $friendsArray = explode(',', $Friends);
    foreach ($friendsArray as $friendUID) {
        $friendUID = trim($friendUID);

        $EmailofFriends = "SELECT * FROM user_details WHERE UID = '$friendUID'";
        $EmailofFriends_run = mysqli_query($link, $EmailofFriends);

        if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
            $fetchemail = mysqli_fetch_assoc($EmailofFriends_run);
            $FriendsEmail = $fetchemail['email'];
            echo 'Friend email: ' . $FriendsEmail . '<br>';
        } else {
            echo 'No friends found'.  '<br>';
        }
    }
} else {
    echo "No friends found for the current user.";
}

mysqli_close($link);
?>
