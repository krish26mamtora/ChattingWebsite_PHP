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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sendtoemail = $_POST['senduseremail'];
    $UID = $_POST['UID'];
    $currentmail = $_POST['currentuser'];
    $CurrentLoginUID = $_POST['CurrentLoginUID'];

    $sql = 'SELECT * FROM user_connections WHERE UID = "' . $UID . '"';
    $result = mysqli_query($link, $sql);
    if ($result) {

        $OldRecieved = 'SELECT * FROM user_connections WHERE UID = "' . $UID . '"';
        $run_OldRecieved = mysqli_query($link, $OldRecieved);
        while ($Old_value = mysqli_fetch_array($run_OldRecieved)) {
            $Old_Received_Compliant = $Old_value['Recieved'];

            if (strpos($Old_value['Recieved'], $CurrentLoginUID) === false) {
                $Old_value['Recieved'] .= " " . $CurrentLoginUID;
            }
            $newReceivedValue = $Old_value['Recieved']; // . ',' . $CurrentLoginUID;


        }

        $NewRecieved = 'UPDATE user_connections SET Recieved = "' . $newReceivedValue . '" WHERE UID="' . $UID . '"';
        $run_NewRecieved = mysqli_query($link, $NewRecieved);

        $OldSent = 'SELECT * FROM user_connections WHERE UID = "' . $CurrentLoginUID . '"';
        $run_OldSent = mysqli_query($link, $OldSent);
        while ($Old_value = mysqli_fetch_array($run_OldSent)) {
            $Old_Sent_Compliant = $Old_value['Sent'];

            if (strpos($Old_value['Sent'], $UID) === false) {
                $Old_value['Sent'] .= " " . $UID;
            }
            $newSentValue = $Old_value['Sent'];
        }

        $NewSend = 'UPDATE user_connections SET Sent = "' . $newSentValue . '" WHERE UID="' . $CurrentLoginUID . '"';
        $run_NewSend = mysqli_query($link, $NewSend);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Friend Request has been sent successfully.</strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      
        mysqli_free_result($result);
    } else {
        echo "No records found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#find').click(function(event) {
                event.preventDefault(); // Prevent the form from submitting normally
                var email = $("input[name='UserEmailtoFind']").val();
                var UID = "<?php echo $CurrentLoginUID; ?>";
                $.post("find_person.php", {
                    suggestions: email,
                    currentlogin: UID

                }, function(data, status) {
                    var apd =  
                    $("#searcheduser").html(data);
                });
            });

            $(document).on('click', '.SendFR', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                $.ajax({
                    type: 'POST',
                    url: 'SendRequest.php',
                    data: form.serialize(),
                    success: function(response) {
                        
                        $('#reqsend').html(response);
                    },
                    error: function() {
                        $('#reqsend').html('An error occurred.');
                    }
                });
            });
        });
    </script>

    <link rel="stylesheet" href="FRtable.css">

</head>

<body>
    <div id="reqsend">
        <form method="POST" class="form-container">
            <h4>Search for Friend</h4>
            <input type="email" name="UserEmailtoFind">
            <button type="submit" name="find" id="find">Find</button>
        </form>
        <div id="searcheduser" style="width: 1150px;     background-color: #f0f0f0;" >

        </div>
        <br>
        <h2>Add to your Friend</h2>
   
    <?php
    // Move PHP code inside the HTML body
    $sql = 'SELECT * FROM user_connections WHERE UID != "' . $CurrentLoginUID . '" ';

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
                    $sentRow = mysqli_fetch_assoc($sentResult);
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
                $SelectEmail = 'SELECT * FROM user_details WHERE  username IS NOT NULL AND UID = "' . $row['UID'] . '" ';
                $SelectEmail_run = mysqli_query($link, $SelectEmail);
                if ($SelectEmail_run && mysqli_num_rows($SelectEmail_run) > 0) {
                    $Allrow = mysqli_fetch_assoc($SelectEmail_run);
                    echo "<tr>";
                    echo "<td>" . $row['UID'] . "</td>";
                    echo "<td>" . $Allrow['username'] . "</td>";
                    echo '<td>' . $Allrow['email'] . '</td>';
                    ?>
                    <td>
                        <form method="POST" action="ViewProfile.php">
                            <input type="hidden" name="senduseremail" value="<?php echo ($Allrow['email']); ?>">
                            <input type="hidden" name="UID" value="<?php echo ($otherUserUID); ?>">
                            <input type="hidden" name="CurrentLoginUID" value="<?php echo ($CurrentLoginUID); ?>">
                            <input type="hidden" name="currentuser" value="<?php echo ($_SESSION["email"]); ?>">
                            <button type="submit" id="ViewProfile" name="ViewProfile">View Profile</button>
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
    mysqli_close($link);
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
