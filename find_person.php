<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['suggestions'])) {
    $emailToFind = $_POST['suggestions'];
    $CurrentLoginUID = $_POST['currentlogin'];
   
    include 'partials/db_connect.php';

    $emailToFind = mysqli_real_escape_string($link, $emailToFind);


    $sql = "SELECT * FROM user_details WHERE email = '$emailToFind'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $UIDtofind = $row['UID'];
        $email = $row['email'];

        echo "<span>Email: $email</span>";

        $sentQuery = 'SELECT * FROM user_connections WHERE UID = "' . $CurrentLoginUID . '"';
            $sentResult = mysqli_query($link, $sentQuery);
            $alreadySent = false;
            $alreadyFriend = false;
            $alreadyReceived = false;
            if ($sentResult && mysqli_num_rows($sentResult) > 0) {
                $sentRow = mysqli_fetch_assoc($sentResult);
                $sentArray = explode(' ', $sentRow['Sent']);
                if (in_array($UIDtofind, $sentArray)) {
                    $alreadySent = true;
                }
                $FriendArray = explode(' ', $sentRow['Friends']);
                if (in_array($UIDtofind, $FriendArray)) {
                    $alreadyFriend = true;
                }
                $ReceivedArray = explode(' ', $sentRow['Recieved']);
                if (in_array($UIDtofind, $ReceivedArray)) {
                    $alreadyReceived = true;
                }
            }

        if ($alreadySent) {
            echo "<button disabled>Already Sent</button>";
        } elseif ($alreadyFriend) {
            echo '<button disabled>Already Friend</button>"';
        } elseif ($alreadyReceived) {
            echo "<button disabled>Friend Request Pending</button>";
        } else {
            echo '<form method="POST" action="SendFriendRequest.php">
                <input type="hidden" name="senduseremail" value="' . $row['email'] . '">
                <input type="hidden" name="UID" value="' . $UIDtofind . '">
                <input type="hidden" name="CurrentLoginUID" value="' . $CurrentLoginUID . '">

                <input type="hidden" name="currentuser" value="' . $_SESSION["email"] . '">
                <button type="submit" name="SendFR">Add Friend</button>
            </form>';

        }
       

    } else {
        echo "<p>User not found.</p>";
    }

    mysqli_close($link);
}
?>
