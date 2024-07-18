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

    // $sendtoemail = $_POST['senduseremail'];
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
    <script src="sendRequest.js"></script>

   <script>
        // $(document).ready(function() {
        //     $('#find').click();
        //     var modalBg = document.getElementById('modalBg');
        //     modalBg.style.display = 'none';
        // });

        // function viewprofile(otherUserUID,othersemail) {
        //     var modalBg = document.getElementById('modalBg');
        //     modalBg.style.display = 'flex';

        //     $.ajax({
        //         type: 'POST',
        //         url: 'ViewProfile.php',
        //         data:{
        //             'UID':otherUserUID,
        //             'senduseremail':othersemail
        //         },
        //         success: function(response) {
        //             document.getElementById('UserProfile').innerHTML = response;
        //         },
        //         error: function(response) {
        //             $('#UserProfile').innerHTML = response;
        //         }
        //     });

        // }

        // function closeModal() {
        //     var modalBg = document.getElementById('modalBg');
        //     modalBg.style.display = 'none';
        // }
        // // function AddFriend(){

        // //     $.ajax({
        // //         type: 'POST',
        // //         url: 'SendRequest.php',
        // //         data:$('#Addfriend').serialize(),
        // //         success: function(response) {
        // //             alert(response);
        // //             // document.getElementById('UserProfile').innerHTML = response;
        // //         },
        // //         error: function(response) {
        // //             // $('#UserProfile').innerHTML = response;

        // //         }
        // //     });       
        // // }

        // $('#find').click(function(event) {

        //     event.preventDefault();

        //     $.ajax({
        //         type: 'POST',
        //         url: 'find_person.php',
        //         data: $('#searchuserform').serialize(),
        //         success: function(response) {
        //             document.getElementById('maindiv').innerHTML = response;
        //         },
        //         error: function(response) {
        //             $('#maindiv').innerHTML = response;
        //         }
        //     });



        //     $(document).on('click', '.SendFR', function(e) {
        //         e.preventDefault();
        //         var form = $(this).closest('form');
        //         $.ajax({
        //             type: 'POST',
        //             url: 'SendRequest.php',
        //             data: form.serialize(),
        //             success: function(response) {
        //                 $('#reqsend').html(response);
        //             },
        //             error: function() {
        //                 $('#reqsend').html('An error occurred.');
        //             }
        //         });
        //     });
        // });

        // function sendfr() {

        //     $.ajax({
        //         type: 'POST',
        //         url: 'SendFriendRequest.php',
        //         data: $('#sendfrform').serialize(),
        //         success: function(response) {
        //             console.log(response)
        //         },
        //         error: function(response) {
        //             console.log(response);
        //         }
        //     });

        // }
        
        
    </script>

    <link rel="stylesheet" href="FRtable.css">

</head>

<body>
    <div id="reqsend">
        <form class="d-flex fd-r" id="searchuserform" mathod="POST">
            <input type="text" name="CurrentLoginUID" value="<?php echo $CurrentLoginUID; ?>" hidden>
            <input class="form-control me-2" type="search" name="tosearch" placeholder="Enter username/email" aria-label="Search" required>
            <button class="btn btn-outline-success" type="button" name="find" id="find">Search</button>
        </form>
        <div id="searcheduser" style="width: 1150px;     background-color: #f0f0f0;">

        </div>
        <br>
        <h2>Add to your Friend</h2>

        <?php
        echo '<div id="maindiv">';
        
        echo '</div>';

        ?>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>