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

            ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form method="POST" action="SendFriendRequest.php">
                            <input type="hidden" name="senduseremail" value="<?php echo $row['email']; ?>">
                            <input type="hidden" name="UID" value="<?php echo $row['UID']; ?>">
                            <input type="hidden" name="CurrentLoginUID" value="<?php echo $CurrentLoginUID; ?>">

                            <input type="hidden" name="currentuser" value="<?php echo $_SESSION["email"]; ?>">
                            <button type="submit" name="SendFR">Add Friend</button>

                        </form>

                    </td>
                </tr>
            <?php
            }
            ?>

        </table>
<?php

        mysqli_free_result($result);
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


mysqli_close($link);

?>




<?php
