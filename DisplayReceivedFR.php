<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Friend Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        table th {
            background-color: #6a5acd;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f1f3f5;
        }

        table tr:hover {
            background-color: #e9ecef;
        }

        button {
            padding: 8px 12px;
            font-size: 14px;
            color: white;
            background-color: #a6a1e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #Accept {
            background-color: #7BE53B;
        }

        #Reject {
            background-color: #FC7858;
        }

        button:disabled {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div>
        <div id="displaymessage">
            <h2>Received Friend Requests</h2>


            <?php
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
            $result1 = mysqli_query($link, $FetchReceivedUID);

            ////


            function RemoveUIDfromReceived($currentUID, $ReceivedUID)
            {
                $link = mysqli_connect("localhost", "root", "", "chattingapp");

                $sql = "SELECT * FROM user_connections WHERE UID= '" . $currentUID . "'";
                $result = mysqli_query($link, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $allreceivedFR = $row['Recieved'];
                        $RemoveFromReceived = str_replace($ReceivedUID, "", $allreceivedFR);
                        $deletefromcurrent = 'UPDATE user_connections SET Recieved = "' . $RemoveFromReceived . '" WHERE UID="' . $currentUID . '"';
                        $deletefromcurrent_run = mysqli_query($link, $deletefromcurrent);
                        if (!$deletefromcurrent_run) {
                            echo "Error updating friend list.";
                        }
                    }
                } else {
                    echo "No matching records found.";
                }
            }
            function RemoveUIDfromSent($currentUID, $ReceivedUID)
            {
                $link = mysqli_connect("localhost", "root", "", "chattingapp");
                $sql = "SELECT * FROM user_connections WHERE UID= '" . $ReceivedUID . "'";
                $result = mysqli_query($link, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $allSentFR = $row['Sent'];
                        $RemoveFromSent = str_replace($currentUID, "", $allSentFR);
                        $deletefromSent = 'UPDATE user_connections SET Sent = "' . $RemoveFromSent . '" WHERE UID="' . $ReceivedUID . '"';
                        $deletefromsent_run = mysqli_query($link, $deletefromSent);
                        if ($deletefromsent_run) {
                            // echo "Updated table";
                        } else {
                            echo "Error updating friend list.";
                        }
                    }
                } else {
                    echo "No matching records found.";
                }
            };

            function AddtoFriends($currentUID, $ReceivedUID)
            {
                $link = mysqli_connect("localhost", "root", "", "chattingapp");
                $sql = "SELECT * FROM user_connections WHERE UID= '" . $currentUID . "'";
                $result = mysqli_query($link, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {

                        $CurrentFriends = $row['Friends'];
                        $AddedintoFriend = trim($CurrentFriends . ' ' . $ReceivedUID, ' ');
                        $AddedintoFriend = preg_replace('/[^0-9\s]/', '', $AddedintoFriend); // Keep only numbers and commas
                        $InsertintoFriend = 'UPDATE user_connections SET Friends = "' . $AddedintoFriend . '" WHERE UID="' . $currentUID . '"';
                        $InsertintoFriend_run = mysqli_query($link, $InsertintoFriend);

                        if ($InsertintoFriend_run) {
                            // echo "Friend list updated successfully.";
                        } else {
                            echo "Error updating friend list.";
                        }
                    }
                } else {
                    echo "No matching records found.";
                }
            };

            // if (isset($_POST['Accept'])) {
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