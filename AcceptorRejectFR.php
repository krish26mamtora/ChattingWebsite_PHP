<?php
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    die("Connection file not found.");
}

if (isset($_POST['Accept'])) {
    $ReceivedMailFrom = $_POST['ReceivedMailFrom'];
    $currentUID = $_POST['currentUID'];
    $ReceivedUID = $_POST['ReceivedUID'];

    $sql = "SELECT * FROM user_connections WHERE UID= '" . $currentUID . "'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $allreceivedFR = $row['Recieved'];
    
            $RemoveFromReceived = str_replace($ReceivedUID, "", $allreceivedFR);
            $RemoveFromReceived = preg_replace('/\s+/', '', $RemoveFromReceived);

            $CurrentFriends = $row['Friends'];
            $AddedintoFriend = trim($CurrentFriends . ',' . $ReceivedUID, ',');
            $AddedintoFriend = preg_replace('/[^0-9,]/', '', $AddedintoFriend); // Keep only numbers and commas
            
            $InsertintoFriend = 'UPDATE user_connections SET Friends = "' . $AddedintoFriend . '" WHERE UID="' . $currentUID . '"';
            $InsertintoFriend_run = mysqli_query($link, $InsertintoFriend);
            
            $deletefromcurrent = 'UPDATE user_connections SET Recieved = "' . $RemoveFromReceived . '" WHERE UID="' . $currentUID . '"';
            $deletefromcurrent_run = mysqli_query($link, $deletefromcurrent);
            if ($deletefromcurrent_run && $InsertintoFriend_run) {
                echo "Friend list updated successfully.";
            } else {
                echo "Error updating friend list.";
            }
        }
    } else {
        echo "No matching records found.";
    }
}
?>
