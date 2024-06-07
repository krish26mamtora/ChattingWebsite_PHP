<?php

session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "connection file not found.";
}

$email = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";

$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    echo '<h2>Add to your Friend</h2>';
}

$sql = 'SELECT * FROM user_details WHERE email != "' . $_SESSION["email"] . '"';

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {

?>

        <table style="border:1px solid black;">
            <tr>
                <th>Users</th>
                <th>Add</th>
            </tr>

            <?php
            while ($row = mysqli_fetch_array($result)) {
                $otherUserUID = $row['UID'];
                $otherUserEmail = $row['email'];
            
                $sentQuery = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
                $sentResult = mysqli_query($link, $sentQuery);
                $alreadySent = false;
                if ($sentResult && mysqli_num_rows($sentResult) > 0) {
                    $sentRow = mysqli_fetch_assoc($sentResult);
                    $sentArray = explode(',', $sentRow['Sent']);
                  
                    if (in_array($otherUserUID, $sentArray)) {
                        $alreadySent = true;            
                    }
                }
              
            ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                    <?php if ($alreadySent) { ?>
                            <button disabled>Already Sent</button>
                        <?php } else { ?>
                            <form method="POST" action="SendFriendRequest.php">
                                <input type="hidden" name="senduseremail" value="<?php echo $otherUserEmail; ?>">
                                <input type="hidden" name="UID" value="<?php echo $otherUserUID; ?>">
                                <input type="hidden" name="CurrentLoginUID" value="<?php echo $CurrentLoginUID; ?>">
                                <input type="hidden" name="currentuser" value="<?php echo $_SESSION["email"]; ?>">
                                <button type="submit" name="SendFR">Add Friend</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>

        </table>
<?php

        mysqli_free_result($result);
    } else {
        echo "No records found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);

?>
