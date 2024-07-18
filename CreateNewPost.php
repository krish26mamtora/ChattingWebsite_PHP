<?php
session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
    exit; 
}

$email = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT UID, username FROM user_details WHERE email = '$email'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    $CurrentLoginname = $row['username'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['postTitle'];
    $content = $_POST['postContent'];
    $postpic = '';

    if (isset($_FILES['postpic']) && $_FILES['postpic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "post_imgs/";
        $uploadFile = $uploadDir . basename($_FILES['postpic']['name']);

        if (move_uploaded_file($_FILES['postpic']['tmp_name'], $uploadFile)) {
            $postpic = $uploadFile;
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:5px;">
                    <strong>Error uploading profile picture!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    $insertQuery = "INSERT INTO posts (UID, uname, title, media, content) VALUES ('$CurrentLoginUID', '$CurrentLoginname', '$title', '$postpic', '$content')";
    $insertResult = mysqli_query($link, $insertQuery);
    // echo $title;
    if ($insertResult) {
        echo '<div style="text-align:center; color:blue; font-size:25px;">Post Added successfully.</div>';
       
    } else {
        echo "Error: " . $insertResult . "<br>" . mysqli_error($link);
    }
}
?>
