<?php
session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
    exit;
}

$currentemail = $_SESSION['email'];

// Get the UID of the currently logged-in user
$UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$currentemail'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    $username = $row['username'];

    // Fetch additional profile details
    $profileQuery = "SELECT * FROM user_profile WHERE UID = '$CurrentLoginUID'";
    $profileResult = mysqli_query($link, $profileQuery);
    if ($profileResult && mysqli_num_rows($profileResult) > 0) {
        $profileRow = mysqli_fetch_assoc($profileResult);
        $country = $profileRow['country'];
        $phone = $profileRow['phone'];
        $about = $profileRow['About'];
        $profilePic = $profileRow['profile_pic'] ? $profileRow['profile_pic'] : 'default-profile.png';
    } else {
        $country = '';
        $phone = '';
        $about = '';
        $profilePic = 'default-profile.png';
    }
} else {
    echo "User not found.";
    exit;
}

if (isset($_POST['savechanges'])) {
    $username = $_POST['username'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $about = $_POST['about'];

    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "profile_pics/";
        $targetFile = $targetDir . basename($_FILES['profilePic']['name']);
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFile)) {
            $UpdateProfile = "UPDATE user_profile SET profile_pic='$targetFile', username='$username', country='$country', phone='$phone', About='$about' WHERE UID=$CurrentLoginUID";
            $UpdateDetails = "UPDATE user_details SET username='$username' WHERE UID=$CurrentLoginUID";
            if (!mysqli_query($link, $UpdateProfile) || !mysqli_query($link , $UpdateDetails)) {
                echo "Error updating record: " . mysqli_error($link);
            } else {
                echo 'profile updated';
            }
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:5px;">
              <strong>Error uploading profile picture!</strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    } else {
        $UpdateProfile = "UPDATE user_profile SET username='$username', country='$country', phone='$phone', About='$about' WHERE UID=$CurrentLoginUID";
        $UpdateDetails = "UPDATE user_details SET username='$username' WHERE UID=$CurrentLoginUID";
        if (!mysqli_query($link, $UpdateProfile) || !mysqli_query($link , $UpdateDetails)) {
            echo "Error updating record: " . mysqli_error($link);
        } else {
            echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:5px;">
              <strong>Profile updated</strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="ProfileStyle.css">
</head>
<body>
    <div id="main">
        <form action="ProfileDetails.php" method="POST" enctype="multipart/form-data">
            <div class="form-group text-center">
                <h2>Please enter your details</h2>
            </div>
            <div class="profile-pic">
                <img id="profileImage" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                <input type="file" id="profilePicInput" name="profilePic" accept="image/*" onchange="loadProfileImage(event)">
                <label for="profilePicInput">Upload Profile Picture</label>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" readonly>
            </div>
            <div class="form-group row">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="<?php echo $country; ?>">
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about" name="about"><?php echo $about; ?></textarea>
            </div>
            <div class="form-group row">
                <button type="submit" name="savechanges" id="Edit">Save</button>
            </div>
        </form>
        <form action="Sidebar.php">
            <button type="submit">Back</button>
        </form>
    </div>
</body>
</html>
