<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        .profile-container {
            display: flex;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .profile-left {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .profile-right {
            flex: 2;
            padding: 20px;
        }

        #profileImage {
            height: 70px;
            width: 70px;
            border-radius: 50%;
        }

        h2,
        p {
            margin: 0;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

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
        $UIDtocheck = $_POST['UIDtocheck'];
        $sentQuery = 'SELECT * FROM user_connections WHERE UID = "' . $CurrentLoginUID . '"';
        $sentResult = mysqli_query($link, $sentQuery);
        $alreadySent = false;
        $alreadyFriend = false;
        $alreadyReceived = false;
        if ($sentResult && mysqli_num_rows($sentResult) > 0) {
            $sentRow = mysqli_fetch_array($sentResult);
            $sentArray = explode(' ', $sentRow['Sent']);
            if (in_array($UIDtocheck, $sentArray)) {
                $alreadySent = true;
            }
            $FriendArray = explode(' ', $sentRow['Friends']);
            if (in_array($UIDtocheck, $FriendArray)) {
                $alreadyFriend = true;
            }
            $ReceivedArray = explode(' ', $sentRow['Recieved']);
            if (in_array($UIDtocheck, $ReceivedArray)) {
                $alreadyReceived = true;
            }
        }

        $sql = "SELECT * FROM user_profile WHERE UID = $UIDtocheck";
        $sql_run = mysqli_query($link, $sql);

        if ($sql_run && mysqli_num_rows($sql_run) > 0) {
            while ($row = mysqli_fetch_assoc($sql_run)) {
                echo '<div class="profile-container">';

                echo '<div class="profile-left">';
                $profilePicPath = $row['profile_pic'];
                echo '<img id="profileImage" src="' . $profilePicPath . '" alt="Profile Picture">';
                echo '<h2>' . $row['username'] . '</h2>';
                echo '</div>';

                echo '<div class="profile-right">';
                echo '<p><strong>About:</strong> ' . $row['About'] . '</p>';
                echo '<p><strong>Phone:</strong> ' . $row['phone'] . '</p>';
                echo '<p><strong>Country:</strong> ' . $row['country'] . '</p>';
                echo '</div>';
                echo '</div>';

    ?>
                <input type="text" id="currentPID" name="currentPID" value="<?php echo $row['UID']; ?>" hidden>
                <?php
                if ($alreadySent) {
                    echo '<button style="background-color: #c2c0da;" class="view_only" disabled>Already Sent</button>';
                } elseif ($alreadyFriend) {
                    echo '<button class="view_only" style="background-color: #c2c0da; " disabled>Already Friend</button>';
                } elseif ($alreadyReceived) {
                    echo '<button class="view_only" style="background-color: #c2c0da; " disabled>Friend Request Pending</button>';
                } else {
                ?>
                    <form method="POST" id="Addfrdform" >
                        <input type="hidden" name="UID" value="<?php echo ($row['UID']); ?>">
                        <input type="hidden" name="CurrentLoginUID" value="<?php echo ($CurrentLoginUID); ?>">
                        <input type="hidden" name="currentuser" value="<?php echo ($_SESSION["email"]); ?>">
                        <button type="button" class="SendFR" onclick="SendFriendRequest('<?php echo ($row['UID']); ?>','<?php echo ($CurrentLoginUID); ?>','<?php echo $_SESSION['email']; ?>')" name="SendFR">Add Friend</button>
                    </form>
    <?php
               } 
            }
        }
    }
   
    ?>
</body>
<script>
   

</script>

</html>