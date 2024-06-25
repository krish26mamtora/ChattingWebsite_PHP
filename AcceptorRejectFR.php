<?php
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    die("Connection file not found.");
}

function RemoveUIDfromReceived($currentUID,$ReceivedUID){
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
function RemoveUIDfromSent($currentUID,$ReceivedUID){
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
                echo "Request Rejected";
            } else {
                echo "Error updating friend list.";
            }
        }
    }else {
        echo "No matching records found.";
    }
};

function AddtoFriends($currentUID,$ReceivedUID){
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
                echo "Friend list updated successfully.";
            } else {
                echo "Error updating friend list.";
            }
        }
    } else {
        echo "No matching records found.";
    }
};

if (isset($_POST['Accept'])) {
    $ReceivedMailFrom = $_POST['ReceivedMailFrom'];
    $currentUID = $_POST['currentUID'];
    $ReceivedUID = $_POST['ReceivedUID'];
    RemoveUIDfromReceived($currentUID,$ReceivedUID);
    RemoveUIDfromSent($currentUID,$ReceivedUID);
    AddtoFriends($currentUID,$ReceivedUID);
    AddtoFriends($ReceivedUID,$currentUID);   
}

if (isset($_POST['reject'])) {
    $ReceivedMailFrom = $_POST['ReceivedMailFrom'];
    $currentUID = $_POST['currentUID'];
    $ReceivedUID = $_POST['ReceivedUID'];
    RemoveUIDfromReceived($currentUID,$ReceivedUID);
    RemoveUIDfromSent($currentUID,$ReceivedUID);
}

?>