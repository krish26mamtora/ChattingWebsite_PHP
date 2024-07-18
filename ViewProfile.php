<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- <link rel="stylesheet" href="ProfileStyle.css"> -->

</head>

<body>
    <div id="main">
        <form method="POST" id="Addfriend">
            <div class="form-group text-center">
                <h2>User's Profile</h2>
            </div>
            <div class="profile-pic">
                <?php

                session_start();
                if (file_exists('partials/db_connect.php')) {
                    include 'partials/db_connect.php';
                } else {
                    echo "connection file not found.";
                }
                $email = $_SESSION['email'];

                $UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$email'";
                $result = mysqli_query($link, $UIDofCurrentLoginUser);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $CurrentLoginUID = $row['UID'];
                    $CurrentLoginName = $row['username'];
                } else {
                    die("User not found.");
                }

                if (isset($_POST['ViewProfile']) || $_SERVER['REQUEST_METHOD']=='POST') {
                    $UserUID = $_POST['UID'];
                    $UserEmail = $_POST['senduseremail'];

                    $sentQuery = 'SELECT * FROM user_connections WHERE UID = "' . $CurrentLoginUID . '"';
                    $sentResult = mysqli_query($link, $sentQuery);
                    $alreadySent = false;
                    $alreadyFriend = false;
                    $alreadyReceived = false;
                    if ($sentResult && mysqli_num_rows($sentResult) > 0) {
                        $sentRow = mysqli_fetch_array($sentResult);
                        $sentArray = explode(' ', $sentRow['Sent']);
                        if (in_array($UserUID, $sentArray)) {
                            $alreadySent = true;
                        }
                        $FriendArray = explode(' ', $sentRow['Friends']);
                        if (in_array($UserUID, $FriendArray)) {
                            $alreadyFriend = true;
                        }
                        $ReceivedArray = explode(' ', $sentRow['Recieved']);
                        if (in_array($UserUID, $ReceivedArray)) {
                            $alreadyReceived = true;
                        }
                    }
                    $FetchUserdata = "SELECT * FROM user_profile WHERE UID = '$UserUID'";
                    $FetchUserdata_run = mysqli_query($link, $FetchUserdata);
                    if ($FetchUserdata_run && mysqli_num_rows($FetchUserdata_run) > 0) {
                        while ($row = mysqli_fetch_assoc($FetchUserdata_run)) {
                            $FindCon = "SELECT * FROM user_connections WHERE UID = '$UserUID'";
                            $FindCon_run = mysqli_query($link, $FindCon);
                            $count = 0;
                            if ($FindCon_run && mysqli_num_rows($FindCon_run) > 0) {
                                while ($friend = mysqli_fetch_assoc($FindCon_run)) {

                                    $pattern = "/\b\d+\b/";
                                    preg_match_all($pattern, $friend['Friends'], $matches);
                                    $totalNumbers = count($matches[0]);
                                }
                            }
                            $profilePicPath = $row['profile_pic'];
                            echo '<img id="profileImage" src="' . $profilePicPath . '" alt="Profile Picture">';

                ?>
            </div>


            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="senduseremail" readonly value="<?php echo $UserEmail; ?>">
            </div>
            <div class="form-group row">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo  $row['username']; ?>" readonly>
                </div>
                <div>
                    <label for="username">Connections</label>
                    <input type="text" id="Connections" name="Connections" value="<?php echo $totalNumbers; ?>" readonly>
                </div>
                <input type="hidden" id="UID" name="UID" value="<?php echo  $UserUID; ?>" readonly>
                <input type="hidden" id="CurrentLoginUID" name="CurrentLoginUID" value="<?php echo  $CurrentLoginUID; ?>" readonly>
                <input type="hidden" id="currentuser" name="currentuser" value="<?php echo  $$email; ?>" readonly>

            </div>
            <div class="form-group row">
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" readonly>
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about" name="about" readonly><?php echo $row['About']; ?></textarea>
            </div>
            <div class="button-container">

            <!-- <button type="button" id="Addfrnd" onclick="AddFriend()">Add Friend</button> -->
            <?php
            if ($alreadySent) {
                            echo '<button style="background-color: #c2c0da; width:40%; margin-bottom: 10px;" class="view_only" disabled>Already Sent</button>';
                        } elseif ($alreadyFriend) {
                            echo '<button class="view_only" style="background-color: #c2c0da;  width:40%; margin-bottom: 10px;" disabled>Already Friend</button>';
                        } elseif ($alreadyReceived) {
                            echo '<button class="view_only" style="background-color: #c2c0da;  width:40%; margin-bottom: 10px;" disabled>Friend Request Pending</button>';
                        } else {
                        ?>
                            <form method="POST" id="Addfrdform" action="SendRequest.php">
                                <input type="hidden" name="senduseremail" value="<?php echo ($UserEmail); ?>">
                                <input type="hidden" name="UID" value="<?php echo ($UserUID); ?>">
                                <input type="hidden" name="CurrentLoginUID" value="<?php echo ($CurrentLoginUID); ?>">
                                <input type="hidden" name="currentuser" value="<?php echo ($_SESSION["email"]); ?>">
                                <button type="button" class="SendFR" name="SendFR" style="width:40%; margin-bottom: 10px;">Add Friend</button>
                            </form>
   
                       <?php } ?>
        </form>
        <br>
   
            <button type="button" id="back" onclick="closeModal()">Back</button>
    </div> </div>
    <script>
     
  function closeModal() {
    var modalBg = document.getElementById('modalprofile');
    modalBg.style.display = 'none';
  }
</script>
</body>

</html>

<?php

                        }
                    }
                }


?>