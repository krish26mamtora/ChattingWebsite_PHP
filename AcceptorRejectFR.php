<?php
session_start();
if (file_exists('partials/db_connect.php')&& file_exists('partials/UpdateConnections.php')) {
    include 'partials/db_connect.php';
    require_once 'partials/UpdateConnections.php';
} else {
    die("Connection file not found.");
}

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