<?php
echo "<h2>Received Friend Request</h2><br>";
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    die("Connection file not found.");
}

$email = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
}

$FetchReceivedUID = "SELECT Recieved FROM user_connections WHERE UID = '$CurrentLoginUID'";
$result = mysqli_query($link, $FetchReceivedUID);
if ($result) {
    while ($FRReceivedUID = mysqli_fetch_assoc($result)) {
        $allReceivedUID = $FRReceivedUID['Recieved'];
        $numbersArray = explode(" ", $allReceivedUID);

        foreach ($numbersArray as $number) {
            $number = trim($number);
            if (!empty($number)) {
                $FetchReceivedEmail = "SELECT email FROM user_details WHERE UID = '$number'";
                $FetchReceivedEmail_run = mysqli_query($link, $FetchReceivedEmail);
                if ($FetchReceivedEmail_run && mysqli_num_rows($FetchReceivedEmail_run) > 0) {
                    while ($row = mysqli_fetch_assoc($FetchReceivedEmail_run)) {
?>
                        <table style="border:1px solid black;">
                            <tr>
                                <th>Recieved</th>
                                <th>Accept</th>
                                <th>Reject</th>
                            </tr>
                            <tr>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <form action="AcceptorRejectFR.php" method="POST">
                                        <input type="hidden" name="ReceivedMailFrom" value="<?php echo $row['email']; ?>">
                                        <input type="hidden" name="ReceivedUID" value="<?php echo $number; ?>">
                                        <input type="hidden" name="currentUID" value="<?php echo $CurrentLoginUID; ?>">
                                        <button name="Accept" type="submit">Accept</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="AcceptorRejectFR.php" method="POST">
                                        <button name="reject" type="submit">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
<?php
                    }
                }
            }
        }
    }
}
?>