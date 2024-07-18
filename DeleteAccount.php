<?php
session_start();
require_once 'DisplayAllFriendsList.php';
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
}
if (isset($_POST['Delete_Account'])) {

    $email = $_SESSION['email'];
    $UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";

    $result = mysqli_query($link, $UIDofCurrentLoginUser);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $CurrentLoginUID = $row['UID'];
    }

    $result1 = delete('user_details',`UID`, $CurrentLoginUID);
    $result2 = delete('user_connections',`UID`, $CurrentLoginUID);
    $result3 = delete('user_profile',`UID`, $CurrentLoginUID);
    $result4 = delete('posts',`UID`, $CurrentLoginUID);

    if ($result1 && $result2 && $result3 && $result4 ) {
        echo "Account deleted successfully.";
        session_unset();
        session_destroy();
        header("Location: Userloginpage.php");
        exit();
    } else {
        echo "Error deleting account.";
    }
}
