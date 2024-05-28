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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#find').click(function(event){
                event.preventDefault();  // Prevent the form from submitting normally
                var email = $("input[name='UserEmailtoFind']").val();
                var UID = "<?php  echo $CurrentLoginUID; ?>";
                $.post("find_person.php", {
                    suggestions: email,
                    currentlogin:UID
                  
                }, function(data, status){
                    $("#search").html(data);
                });
            });
        });
    </script>

    <link rel="stylesheet" href="FRtable.css">

</head>
<body>
<form method="POST" class="form-container"><h4>Search for Friend</h4>
    <input type="email" name="UserEmailtoFind">
    <button type="submit" name="find" id="find">Find</button>
</form>
<div id="search" style="height:90px; width:700px;">

</div>
<br>
<h2>Add to your Friend</h2>
</body>
</html>

<?php
$sql = 'SELECT * FROM user_connections WHERE UID != "' . $CurrentLoginUID . '"';

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div id="maindiv">';
        echo "<table>";
        echo "<tr>";
        echo "<th>id</th>";
        echo "<th>Email</th>";
     
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
            echo "<tr>";
            echo "<td>" . $row['UID'] . "</td>";
            $SelectEmail = 'SELECT * FROM user_details WHERE UID = "' . $row['UID'] . '"';
            $SelectEmail_run = mysqli_query($link, $SelectEmail);
            if ($SelectEmail_run && mysqli_num_rows($SelectEmail_run) > 0) {
                $Allrow = mysqli_fetch_assoc($SelectEmail_run);
                echo '<td>' . $Allrow['email'] . '</td>';
            }
     
            echo "<td>";
            if ($alreadySent) {
                echo '<button disabled>Already Sent</button>';
            } elseif ($alreadyFriend) {
                echo '<button disabled>Already Friend</button>';
            } elseif ($alreadyReceived) {
                echo '<button disabled>Friend Request Pending</button>';
            } else {
                echo '<form method="POST" action="SendFriendRequest.php">
                    <input type="hidden" name="senduseremail" value="' . $Allrow['email'] . '">
                    <input type="hidden" name="UID" value="' . $otherUserUID . '">
                    <input type="hidden" name="CurrentLoginUID" value="' . $CurrentLoginUID . '">
                    <input type="hidden" name="currentuser" value="' . $_SESSION["email"] . '">
                    <button type="submit" name="SendFR">Add Friend</button>
                </form>';
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        mysqli_free_result($result);
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not execute $sql. " . mysqli_error($link);
}

mysqli_close($link);

?>
