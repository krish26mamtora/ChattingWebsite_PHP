<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Friend Requests</title>
    <link rel="stylesheet"  href="style_DisplayReceivedFR.css">

</head>
<body>
    <div>
        <div id="displaymessage">
            <h2>Received Friend Requests</h2>

            <?php
            // session_start();
            if (file_exists('partials/db_connect.php')&& file_exists('partials/UpdateConnections.php')) {
                include 'partials/db_connect.php';
                require_once 'partials/UpdateConnections.php';

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
            $result1 = mysqli_query($link, $FetchReceivedUID);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $ReceivedMailFrom = $_POST['ReceivedMailFrom'];
                $currentUID = $_POST['currentUID'];
                $ReceivedUID = $_POST['ReceivedUID'];
                if (isset($_POST['operation']) && ($_POST['operation'] == "accept")) {
                    RemoveUIDfromReceived($currentUID, $ReceivedUID);
                    RemoveUIDfromSent($currentUID, $ReceivedUID);
                    AddtoFriends($currentUID, $ReceivedUID);
                    AddtoFriends($ReceivedUID, $currentUID);
                    echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Friend Request Accepted!</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        ';
                } else if (isset($_POST['operation']) && ($_POST['operation'] == "reject")) {
                    RemoveUIDfromReceived($currentUID, $ReceivedUID);
                    RemoveUIDfromSent($currentUID, $ReceivedUID);

                    echo '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Friend request rejected</strong> 
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

        ';
                }
            }

            if ($result1) {
                while ($FRReceivedUID = mysqli_fetch_assoc($result1)) {
                    $allReceivedUID = $FRReceivedUID['Recieved'];
                    $numbersArray = explode(" ", $allReceivedUID);

                    echo '
                    <table>
                            <tr>
                                <th>Email</th>
                                <th>Accept</th>
                                <th>Reject</th>
                            </tr>
                ';
                    foreach ($numbersArray as $number) {
                        $number = trim($number);
                        if (!empty($number)) {
                            $FetchReceivedEmail = "SELECT email FROM user_details WHERE UID = '$number'";
                            $FetchReceivedEmail_run = mysqli_query($link, $FetchReceivedEmail);
                            if ($FetchReceivedEmail_run && mysqli_num_rows($FetchReceivedEmail_run) > 0) {
                                // while ($take_row = mysqli_fetch_assoc($FetchReceivedEmail_run)) {
                                $take_row = mysqli_fetch_assoc($FetchReceivedEmail_run);
            ?>
                                <tr>
                                    <td><?php echo ($take_row['email']); ?></td>
                                    <td>
                                        <form id="Addfrdform" method="POST">
                                            <input type="hidden" name="ReceivedMailFrom" value="<?php echo ($take_row['email']); ?>">
                                            <input type="hidden" name="ReceivedUID" value="<?php echo ($number); ?>">
                                            <input type="hidden" name="currentUID" value="<?php echo ($CurrentLoginUID); ?>">
                                            <input type="hidden" name="operation" value="accept">
                                            <button name="Accept" id="Accept" type="button">Accept</button>
                                        </form>

                                    </td>
                                    <td>
                                        <form id="rjctfrdform" method="POST">
                                            <input type="hidden" name="ReceivedMailFrom" value="<?php echo ($take_row['email']); ?>">
                                            <input type="hidden" name="ReceivedUID" value="<?php echo ($number); ?>">
                                            <input type="hidden" name="currentUID" value="<?php echo ($CurrentLoginUID); ?>">
                                            <input type="hidden" name="operation" value="reject">
                                            <button name="reject" id="Reject" type="button">Reject</button>
                                        </form>
                                    </td>
                                </tr>
            <?php
                            }
                        }
                    }
                }
            } else {
                echo "<tr><td colspan='3'>No friend requests found.</td></tr>";
            }
            mysqli_close($link);
            ?>
            </table>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
    $(document).ready(function() {
        $('#Accept').on('click', function() {
            $.ajax({
                type: 'post',
                url: 'DisplayReceivedFR.php',
                data: $('#Addfrdform').serialize(),
                success: function(response) {
                    $('#displaymessage').html(response);
                },
                error: function() {
                    console.log("error occured");
                }
            });
        });
        $('#Reject').on('click', function() {
            $.ajax({
                type: 'post',
                url: 'DisplayReceivedFR.php',
                data: $('#rjctfrdform').serialize(),
                success: function(response) {
                    $('#displaymessage').html(response);
                },
                error: function() {
                    console.log("error occured");
                }
            });
        });

    });
</script>

</html>