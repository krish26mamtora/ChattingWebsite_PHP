<?php
// session_start();
if (file_exists('partials/db_connect.php') && file_exists('partials/UpdateConnections.php')) {
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
} else {
    die("Could not retrieve UID for the current user.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CurrentLoginUID = $_POST['CurrentLoginUID'];
    $FriendUID = $_POST['FriendUID'];
    RemoveUIDfromFriend($CurrentLoginUID, $FriendUID);
    RemoveUIDfromFriend($FriendUID, $CurrentLoginUID);

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Person has been removed From Your Friends</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

$UIDofFriends = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
$result = mysqli_query($link, $UIDofFriends);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Friends</title>
    <link rel="stylesheet" href="style_DisplayAllFriendList.css">

</head>

<body>
    <div id="alldata">
        <h2>Your Friends</h2>
        <?php
        $hasFriends = false;
        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)){
            $Friends = $row['Friends'];

            if (!empty(trim($Friends))) {
                $friendsArray = explode(' ', $Friends);
                foreach ($friendsArray as $friendUID) {
                    $friendUID = trim($friendUID);

                    if (!empty($friendUID)) {
                        $EmailofFriends = "SELECT email,username FROM user_details WHERE UID = '$friendUID'";
                        $EmailofFriends_run = mysqli_query($link, $EmailofFriends);

                        if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
                            if (!$hasFriends) {
                                $hasFriends = true;
                                echo '<div id="alldata">';
                                echo '<table>
                                    <tr>
                                        <th>Email</th>
                                        <th>username</th>

                                        <th>Profile</th>
                                        <th>Chat</th>

                                        <th>Action</th>

                                    </tr>';
                            }

                            $fetchemail = mysqli_fetch_assoc($EmailofFriends_run);
                            $FriendsEmail = $fetchemail['email'];
                            $FriendsUname = $fetchemail['username'];
        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($FriendsUname); ?></td>
                            <td><?php echo htmlspecialchars($FriendsEmail); ?></td>
                                <td>

                                    <form id="viewprofileform"  method="POST">
                                     
                                        <button type="button" id="ViewProfile" onclick="viewprofile('<?php echo htmlspecialchars($friendUID); ?>','<?php echo htmlspecialchars($FriendsEmail); ?>')" name="ViewProfile">View Profile</button>
                                    </form>

                                </td>
                                <td>

                                    <form id="chatform" action="Twopersonchat.php" method="POST">
                                        <input type="hidden" name="frdUID" value="<?php echo htmlspecialchars($friendUID); ?>">
                                        <input type="hidden" name="currUID" value="<?php echo htmlspecialchars($CurrentLoginUID); ?>">
                                        <button type="submit" id="chat" name="chat">Start chat</button>
                                    </form>


                                </td>
                                <td>
                                    <form id="myForm" method="POST">
                                        <input type="hidden" name="FriendUID" value="<?php echo htmlspecialchars($friendUID); ?>">
                                        <input type="hidden" name="CurrentLoginUID" value="<?php echo htmlspecialchars($CurrentLoginUID); ?>">
                                        <button type="button" id="RemoveFriend" name="RemoveFriend">Remove Friend</button>
                                    </form>


                                    <script>
                                        $(document).ready(function() {
                                            $('#RemoveFriend').on('click', function(e) {
                                                e.preventDefault();
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'DisplayAllFriendsList.php',
                                                    data: $('#myForm').serialize(),

                                                    success: function(response) {
                                                        $('#alldata').html(response);
                                                    },
                                                    error: function() {
                                                        $('#alldata').html('error occured');
                                                    }
                                                });
                                            });
                                        });

                                        function display(friendUID, currentUID) {

                                            $.ajax({
                                                url: 'Twopersonchat.php',
                                                type: 'POST',
                                                data: {
                                                    frdUID: friendUID,
                                                    currUID: currentUID
                                                },
                                                success: function(response) {
                                                    $('#right').html(response);
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('AJAX Error: ' + status + error);
                                                }
                                            });
                                        }

                                        function closeprofile() {
                                            var modalBg = document.getElementById('modalprofile');
                                            modalBg.style.display = 'none';
                                        }
                                        function viewprofile(UID,senduseremail){
                                            var modalBg = document.getElementById('modalprofile');
                                            modalBg.style.display = 'flex';
                                            
                                            $.ajax({
                                                url: 'ViewProfile.php',
                                                type: 'POST',
                                                data:{
                                                    'UID':UID,
                                                    'senduseremail':senduseremail,
                                                },
                                                success: function(response) {
                                                    $('#displaypostprofile').html(response);
                                                },
                                                error: function(xhr, status, error) {
                                                    $('#displaypostprofile').html(response);
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
        <?php
                        }
                    }
                }
            }
        }
        }
        if ($hasFriends) {
            echo '</table>';
        } else {
            echo "<p>No friends found for the current user.</p>";
        }
        mysqli_close($link);
        ?>
    </div>


    <div class="modal-bg" id="modalprofile">
        <div class="leftspace"></div>
        <div class="modal-content" id="modal-comments">
            <span class="close-btn" onclick="closeprofile()">&times;</span>
            <!-- <div class="ctnttl">
                <h2>Profile</h2>
            </div> -->
            <div id="displaypostprofile">

            </div>


        </div>
        <div class="rightspace"></div>
    </div>

</body>

</html>