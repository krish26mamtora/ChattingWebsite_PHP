<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="FRtable.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style_FindPersonModal.css">
  
</head>

<body>

    <?php
    session_start();
    function DisplayUsers($sql, $CurrentLoginUID)
    {
        $link = mysqli_connect("localhost", "root", "", "chattingapp");

        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                echo '<div id="maindiv">';
                echo "<table>";
                echo "<tr>";
                echo "<th>id</th>";
                echo "<th>Username</th>";
                echo "<th>Email</th>";
                echo "<th>View Profile</th>";
                echo "<th>Add</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_array($result)) {
                    $otherUserUID = $row['UID'];
                    $sentQuery = 'SELECT * FROM user_connections WHERE UID = "' . $CurrentLoginUID . '"';
                    $sentResult = mysqli_query($link, $sentQuery);
                    $alreadySent = false;
                    $alreadyFriend = false;
                    $alreadyReceived = false;
                    if ($sentResult && mysqli_num_rows($sentResult) > 0) {
                        $sentRow = mysqli_fetch_array($sentResult);
                        $sentArray = explode(' ', $sentRow['Sent']);
                        if (in_array($otherUserUID, $sentArray)) {
                            $alreadySent = true;
                        }
                        $FriendArray = explode(' ', $sentRow['Friends']);
                        if (in_array($otherUserUID, $FriendArray)) {
                            $alreadyFriend = true;
                        }
                        $ReceivedArray = explode(' ', $sentRow['Recieved']);
                        if (in_array($otherUserUID, $ReceivedArray)) {
                            $alreadyReceived = true;
                        }
                    }
                    $SelectEmail = 'SELECT user_details.email, user_profile.profile_pic, user_profile.username, user_profile.UID
                    FROM user_details
                    INNER JOIN user_profile ON user_details.UID = user_profile.UID
                    WHERE user_profile.username IS NOT NULL AND user_profile.UID = "' . $row['UID'] . '"';

                    $SelectEmail_run = mysqli_query($link, $SelectEmail);
                    if ($SelectEmail_run && mysqli_num_rows($SelectEmail_run) > 0) {
                       while( $Allrow = mysqli_fetch_array($SelectEmail_run)){
                       
                        $altText = 'profile image';
                        echo "<tr>";
                        echo "<td>" . '<input type="image" id="profile_image" src="' . $Allrow['profile_pic'] . '" alt="' . $altText . '" name="'  . '">' . "</td>";
                        echo "<td>" . $Allrow['username'] . "</td>";
                        echo '<td>' . $Allrow['email'] . '</td>';
    ?>
                        <td>
                            <form method="POST" id="viewprofileform">
                                <input type="hidden" name="senduseremail" value="<?php echo ($Allrow['email']); ?>">
                                <input type="hidden" name="UID" value="<?php echo ($otherUserUID); ?>">
                                <input type="hidden" name="CurrentLoginUID" value="<?php echo ($CurrentLoginUID); ?>">
                                <input type="hidden" name="currentuser" value="<?php echo ($_SESSION["email"]); ?>">
                                <button type="button" id="ViewProfile" onclick="viewprofile('<?php echo ($otherUserUID); ?>','<?php  echo $Allrow['email'] ;?>')" name="ViewProfile">View Profile</button>
                                </form>

                        </td>
                        <?php
                        echo "<td>";
                        if ($alreadySent) {
                            echo '<button style="background-color: #c2c0da;" class="view_only" disabled>Already Sent</button>';
                        } elseif ($alreadyFriend) {
                            echo '<button class="view_only" style="background-color: #c2c0da; " disabled>Already Friend</button>';
                        } elseif ($alreadyReceived) {
                            echo '<button class="view_only" style="background-color: #c2c0da; " disabled>Friend Request Pending</button>';
                        } else {
                        ?>
                            <form method="POST" id="Addfrdform" action="SendRequest.php">
                                <input type="hidden" name="senduseremail" value="<?php echo ($Allrow['email']); ?>">
                                <input type="hidden" name="UID" value="<?php echo ($otherUserUID); ?>">
                                <input type="hidden" name="CurrentLoginUID" value="<?php echo ($CurrentLoginUID); ?>">
                                <input type="hidden" name="currentuser" value="<?php echo ($_SESSION["email"]); ?>">
                                <button type="button" class="SendFR" name="SendFR">Add Friend</button>
                            </form>
    <?php
                        }
                        echo "</td>";
                        echo "</tr>";
                    }

                }
                }
                echo "</table>";
                echo "</div>";
                echo "</div>";
                mysqli_free_result($result);
            } else {
                echo "No records found.";
            }
        } else {
            echo "ERROR: Could not execute $sql. " . mysqli_error($link);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $emailToFind = $_POST['tosearch'];
        $CurrentLoginUID = $_POST['CurrentLoginUID'];

        include 'partials/db_connect.php';

        $emailToFind = mysqli_real_escape_string($link, $emailToFind);

        if ($emailToFind && $emailToFind !== '') {

            $sql = "SELECT * FROM user_details WHERE email = '$emailToFind' OR username = '$emailToFind' ";
            DisplayUsers($sql, $CurrentLoginUID);
        } else {
            $sql = 'SELECT * FROM user_connections WHERE UID != "' . $CurrentLoginUID . '" ';

            DisplayUsers($sql, $CurrentLoginUID);
        }
        mysqli_close($link);
    }
    ?>


    <div class="modal-bg" id="modalBg">
        <div class="leftspace"></div>
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>


            <div id="UserProfile">
                <div id="display_msg"></div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

    </script>

</body>

</html>