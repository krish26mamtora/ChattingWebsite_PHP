<?php
session_start();
if (isset($_POST['Delete_Account'])) {

    if (file_exists('partials/db_connect.php')) {
        include 'partials/db_connect.php';
    } else {
        echo "Connection file not found.";
    }

    $email = $_SESSION['email'];
    $UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";

    $result = mysqli_query($link, $UIDofCurrentLoginUser);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $CurrentLoginUID = $row['UID'];
    }

    $delfromdetails = "DELETE FROM `user_details` WHERE `UID` = '$CurrentLoginUID'";
    $delfromconnections = "DELETE FROM `user_connections` WHERE `UID` = '$CurrentLoginUID'";
    $delfromprofile = "DELETE FROM `user_profile` WHERE `UID` = '$CurrentLoginUID'";

    $result1 = mysqli_query($link, $delfromdetails);
    $result2 = mysqli_query($link, $delfromconnections);
    $result3 = mysqli_query($link, $delfromprofile);


    if ($result1 && $result2 && $result3) {
        echo "Account deleted successfully.";
        session_unset();
        session_destroy();
        header("Location: Userloginpage.php");
        exit();
    } else {
        echo "Error deleting account.";
    }
}
?>