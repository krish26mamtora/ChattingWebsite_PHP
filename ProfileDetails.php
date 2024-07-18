<?php
session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
    exit;
}

$currentemail = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$currentemail'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    $username = $row['username'];

    $profileQuery = "SELECT * FROM user_profile WHERE UID = '$CurrentLoginUID'";
    $profileResult = mysqli_query($link, $profileQuery);
    if ($profileResult && mysqli_num_rows($profileResult) > 0) {
        $profileRow = mysqli_fetch_assoc($profileResult);
        $country = $profileRow['country'];
        $phone = $profileRow['phone'];
        $about = $profileRow['About'];
        $profilePic = ($profileRow['profile_pic'] && file_exists($profileRow['profile_pic'])) ? $profileRow['profile_pic'] : 'dummy.jpg';
    } else {
        $country = '';
        $phone = '';
        $about = '';
        $profilePic = 'dummy.jpg';
    }
} else {
    echo "User not found.";
    exit;
}

if ((isset($_POST['savechanges'])) || ($_SERVER['REQUEST_METHOD'] == 'POST')) {
    $username = $_POST['username'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $about = $_POST['about'];
    if (!isset($_POST['AccountType']) || empty($_POST['AccountType'])) {
        $AccountType = 'public'; 
    } else {
        $AccountType = $_POST['AccountType'];
    }

    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "profile_pics/";
        $targetFile = $targetDir . basename($_FILES['profilePic']['name']);
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFile)) {
            $profilePic = $targetFile;
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:5px;">
                <strong>Error uploading profile picture!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }

    $UpdateProfile = "UPDATE user_profile SET profile_pic='$profilePic', username='$username', country='$country', phone='$phone', About='$about' WHERE UID=$CurrentLoginUID";
    $UpdateDetails = "UPDATE user_details SET username='$username',type='$AccountType' WHERE UID=$CurrentLoginUID";
    if (!mysqli_query($link, $UpdateProfile) || !mysqli_query($link, $UpdateDetails)) {
        echo "Error updating record: " . mysqli_error($link);
    } else {

        $_SESSION['profile'] = 'Profile updated successfully!!';
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

    <script>
        // Function to load the selected profile image
        function loadProfileImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</head>

<body>

    <div id="all">
        <div id="displaymsg">
            <?php
            if (isset($_SESSION['profile']) && !empty($_SESSION['profile'])) {
                echo $_SESSION['profile'];
            }
            ?>
        </div>

        <div id="main">

            <div id="allcontent">
                <form action="ProfileDetails.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group text-center" id="heading">
                        <h2>Please enter your details</h2>
                    </div>
                    <div class="profile-pic">
                        <img id="profileImage" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                        <input type="file" id="profilePicInput" name="profilePic" accept="image/*" onchange="loadProfileImage(event)">
                        <label for="profilePicInput">Upload Profile Picture</label>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
                    </div>
                    <div class="form-group row">
                        <div>
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>
                        <div>
                            <label for="mySelect">Account type</label>
                            <select class="form-select" id="AccountType" name="AccountType" aria-label="My select menu" required>
                                <option value="public">Public</option>
                                <option value="private">Private</option>         
                            </select>
                        </div>


                    </div>
                    <div class="form-group row">
                        <div>
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>">
                        </div>
                        <div>
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="about">About</label>
                        <textarea id="about" name="about"><?php echo htmlspecialchars($about); ?></textarea>
                    </div>

                    <div class="form-group row">
                        <button type="submit" id="savechanges" name="savechanges" id="Edit">Save</button>
                    </div>
                    <?php if (isset($_SESSION['profile']) && !empty($_SESSION['profile'])) : ?>

                        <button type="button" id="backButton" name="back">Back</button>

                    <?php endif; ?>
                </form>



            </div>
        </div>
    </div>
    <script>
        document.getElementById('backButton').addEventListener('click', function() {
            window.location.href = 'Sidebar.php';
        });
    </script>


</body>

</html>