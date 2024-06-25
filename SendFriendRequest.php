<?php
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "connection file not found.";
}

if (isset($_POST['SendFR'])) {
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
        echo "friend request has been send and last page is opening";
        ?>
        
        <?php
        mysqli_free_result($result);
    } else {
        echo "No records found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
