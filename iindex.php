<?php
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
 <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        body{
            background-color:#282828;
        }
        #main {
            font-family: 'Poppins', sans-serif;
margin-left: -20px;
            height: 100%;
            width: 100%;
            background-color: antiquewhite;
            display: flex;
            align-items: center;
            border-radius: 20px;
          
        }

        #left {
            height: 650px;
            width: 20%;
            background-color: #dbe2ef;

            border-radius: 20px 0 0 20px;
            padding: 15px;
          
        }

        .takemsg {
            height: 40px;
            width: 600px;
            background-color: rgb(82, 237, 240);
            display: flex;
            align-items: center;
            justify-content: center;
            

        }

        .message-container {
            height: 20px;
            width: 180px;
            background-color: chocolate;
        }

        .friends-container {
            display: flex;
            align-items: center;
            flex-direction: row;
        }

        .friends-container form {
            display: flex;
            align-items: center;
        }

        #right {
            height: 650px;
            width: 100%;
            background-color: #cfcbee;
            border-radius: 0px 20px 20px 0px;
            /* box-shadow: 0px 0px 1px 1px rgba(100, 100, 100, 0.5); White shadow effect */

        }
        #btndisfrd {
        height: 50px;
        width: 260px;
        border-radius: 5px;
        border: 2px solid #cfcbee; 
        margin-bottom: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        transition: box-shadow 0.3s ease, transform 0.3s ease; 
        font-size: 17px; 
        font-weight: bold; 
        background-color: white;
        color: #333; 
        text-align: center; 
    }
    #btndisfrd:hover {
            background-color: #f7f7ff;
            transform: scale(1.00001); 
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    }
    #searchfrnd{
        border-radius: 7px;
        /* background-color:  #e6e6ff; */
        height: 35px;
        border:1px solid white;

    }
    </style>
</head>

<body>
    <div id="main">
        <div id="left">
            <h2>Your Friends</h2>
            <div>
                <input type="text" id="searchfrnd" placeholder="Search friend..">
                
            </div>
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

                    $NameofFriends = "SELECT * FROM user_details WHERE UID = '$friendUID'";
                    $EmailofFriends_run = mysqli_query($link, $NameofFriends);

                    if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
                        $fetcheUID = mysqli_fetch_assoc($EmailofFriends_run);
                        $FriendsName = $fetcheUID['username'];
            ?>
            
            <tr>
            
                
                <div id="displayfriends" class="friends-container">
                    <!-- <h4 id="DisplayFriendName"><?php //echo $FriendsName . '  '; ?></h4> -->
                    <button type="button" id="btndisfrd" onclick='display("<?php echo $friendUID; ?>", "<?php echo $CurrentLoginUID; ?>")'><?php echo $FriendsName . '  '; ?></button>
        
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
