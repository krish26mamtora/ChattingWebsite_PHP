<?php
// if(!PHP_SESSION_ACTIVE){

    session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    die("Connection file not found.");
}

$email = $_SESSION['email'];

$UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$email'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $CurrentLoginUID = $row['UID'];
    $CurrentLoginName = $row['username'];
} else {
    die("User not found.");
}

{
    if(isset($_POST['chat'])){
        
        $FriendUID_r = $_POST['FriendUID'];
        $CurrentLoginUID_r = $_POST['CurrentLoginUID'];
        ?>
        <script>
            alert("<?php echo $FriendUID_r; ?>"+"<?php echo $CurrentLoginUID_r; ?>");
            display("<?php echo $FriendUID_r; ?>", "<?php echo $CurrentLoginUID_r; ?>");
            </script>

<?php
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet"  href="style_chat.css">
</head>

<body>
    <div id="main">
        <div id="left">
            <h3>Your Friends</h3>
            
            <hr>
            
            <table>
           
            <?php
            $display = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
            $display_run = mysqli_query($link, $display);
            if ($display_run && mysqli_num_rows($display_run) > 0) {
                $user = mysqli_fetch_assoc($display_run);
                $Friends = $user['Friends'];

                $friendsArray = explode(' ', $Friends);
                foreach ($friendsArray as $friendUID) {
                    $friendUID = trim($friendUID);

                    $NameofFriends = "SELECT * FROM user_profile WHERE UID = '$friendUID'";
                    $EmailofFriends_run = mysqli_query($link, $NameofFriends);

                    if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
                        $fetcheUID = mysqli_fetch_assoc($EmailofFriends_run);
                        $FriendsName = $fetcheUID['username'];
                        $profileimg = '<img id="profileimg" src="' . $fetcheUID['profile_pic'] . '" alt="profilepic">';
            ?>
            
            <tr>
            
                
                <div id="displayfriends" class="friends-container">
                    <button type="button" id="btndisfrd" onclick='display("<?php echo $friendUID; ?>", "<?php echo $CurrentLoginUID; ?>")'><?php echo $profileimg.' '.$FriendsName . '  '; ?></button>
               
                </div>
                </tr>
            <?php
                    }
                }
            }
            ?>
            </table>
        </div>
        <div id="right">
            <img id="logoimg" src="logoimg.png" alt="logo">
            <h3></h3>
        </div>
    </div>

    <script>
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
    </script>
</body>

</html>
