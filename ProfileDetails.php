<?php
session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
}

$currentemail = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$currentemail'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    $username = $row['username'];
    if(empty($username)){
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
                <img id="profileImage" src="default-profile.png" alt="Profile Picture">
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
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div class="form-group row">
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country">
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
            </div>
            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about" name="about"></textarea>
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

<?php
    }
} else {
    echo "already done";
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
                echo "Profile updated successfully.";
            }
        } else {
            echo "Error uploading profile picture.";
        }
    }
    
}
?>
