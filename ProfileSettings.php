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



if (isset($_POST['Delete_Account'])) {

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
    $delfromposts = "DELETE FROM `posts` WHERE `UID` = '$CurrentLoginUID'";

    $sendeachuser = "SELECT UID,Friends from `user_connections` where `UID` != '$CurrentLoginUID'";
    $result5 = mysqli_query($link, $sendeachuser);
    if (mysqli_num_rows($result5) > 0) {
        while ($row = mysqli_fetch_array($result5)) {
            $AllFriends = $row['Friends'];
            $RemoveFromFriend = str_replace($CurrentLoginUID, "", $AllFriends);
            $deletefromFriend = 'UPDATE user_connections SET Friends = "' . $RemoveFromFriend . '" WHERE UID="' . $row['UID'] . '"';
            $deletefromsent_run = mysqli_query($link, $deletefromFriend);
            if (!$deletefromsent_run) 
            {
                echo "Error updating friend list.";
            }
        }
    } else {
        echo "No matching records found.";
    }


    $result1 = mysqli_query($link, $delfromdetails);
    $result2 = mysqli_query($link, $delfromconnections);
    $result3 = mysqli_query($link, $delfromprofile);
    $result4 = mysqli_query($link, $delfromposts);
    // $result5 = mysqli_query($link, $removefromfriends);

    if ($result1 && $result2 && $result3 && $result4 && $result5) {
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="style_profilesettings.css">
    <style>

    </style>
    <script>
        function loadProfileImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function closeModal() {
            document.getElementById('id01').style.display = 'none';
        }
    </script>

</head>

<body>
    <div id="main">
        <div id="allcontent">
            <form action="ProfileSettings.php" method="POST" id="profileform" enctype="multipart/form-data">

                <!-- <form action="ProfileDetails.php" method="POST" enctype="multipart/form-data"> -->
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
                    <button type="button" name="savechanges" id="Edit">Save</button>
                </div>
            </form>

            <div class="button-row">
                <form action="logout.php">
                    <button type="submit" id="logoutbtn">logout</button>
                </form>
                <button id="deletebtnask" onclick="document.getElementById('id01').style.display='block'">Delete Account</button>
            </div>

            <div class="container">
                <div id="id01" class="modal">
                    <span onclick="closeModal()" class="close" title="Close Modal">&times;</span>
                    <form id="deleteAccountForm" class="modal-content" action="ProfileSettings.php" method="POST">
                        <div class="container">
                            <h1>Delete Account</h1>
                            <p>Your all data will be deleted , Are you sure you want to delete your account? </p>
                            <div class="clearfix">
                                <button type="button" class="cancelbtn" onclick="closeModal()">Cancel</button>
                                <button type="submit" name="Delete_Account" class="deletebtn">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#Edit').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'ProfileDetails.php',
                data: $('#profileform').serialize(),
                success: function(response) {
                    // alert('success: ' + response);
                },
                error: function(response) {
                    alert('alert: ' + response);
                }
            });

        });
       
    });
</script>

</html>

<?php


// if(isset($_POST['viewposts'])){
//     $CurrentLoginUID = $_POST['currentlogin'];
//     echo $CurrentLoginUID;
// }
?>