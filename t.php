<?php
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
    exit; // Exit the script if connection file is not found
}

if ((isset($_POST['savechanges'])) || ($_SERVER['REQUEST_METHOD'] == 'POST')) {
    $username = $_POST['username'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $about = $_POST['about'];
    echo $username;
    // if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    //     $targetDir = "profile_pics/";
    //     $targetFile = $targetDir . basename($_FILES['profilePic']['name']);
    //     if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFile)) {
    //         $profilePic = $targetFile;
    //     } else {
    //         echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:5px;">
    //             <strong>Error uploading profile picture!</strong>
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>';
    //     }
    // }

    // $UpdateProfile = "UPDATE user_profile SET profile_pic='$profilePic', username='$username', country='$country', phone='$phone', About='$about' WHERE UID=$CurrentLoginUID";
    // $UpdateDetails = "UPDATE user_details SET username='$username' WHERE UID=$CurrentLoginUID";
    // if (!mysqli_query($link, $UpdateProfile) || !mysqli_query($link, $UpdateDetails)) {
    //     echo "Error updating record: " . mysqli_error($link);
    // } else {

    //     $_SESSION['profile'] = 'Profile updated successfully!!';
       
    
    // }
}
?>
