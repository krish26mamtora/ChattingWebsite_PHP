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

if (isset($_POST['frdUID']) && isset($_POST['currUID'])) {
    $frdUID = $_POST['frdUID'];
    // echo $frdUID;
    $currUID = $_POST['currUID'];
    // echo 'Current login UID: ' . $currUID . '<br>';
    // echo 'Sender UID: ' . $frdUID . '<br>';
}
if (isset($_POST['chat'])) {
    // include 'iindex.php';
}
if (isset($_POST['action']) && $_POST['action'] == 'send_message') {
    $name = $_POST['name'];
    $msg = $_POST['msg'];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    // echo $sender;
    insertMessage($name, $msg, $sender, $receiver);

    exit;
}

function insertMessage($name, $msg, $sender, $receiver)
{
    $dsn = 'mysql:host=localhost;dbname=chattingapp';
    $username = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        $stmt = $pdo->prepare("INSERT INTO message (username, Sender,Receiver,msg) VALUES (:name, :Sender, :Receiver ,:message)");
        $stmt->execute([':name' => $name, ':Sender' => $sender, ':Receiver' => $receiver, ':message' => $msg]);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
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
   <link rel="stylesheet"  href="style_chatRS.css">
</head>

<body>

    <div id="top">

        <?php

        if (!empty($frdUID)) {

            $sql = "SELECT * FROM user_details WHERE UID = '$frdUID'";
            $sql_run = mysqli_query($link, $sql);
            if ($sql_run && mysqli_num_rows($sql_run) > 0) {
                $user = mysqli_fetch_assoc($sql_run);

                $Friends = $user['username'];

                $profilepic = "SELECT * FROM user_profile WHERE UID = '$frdUID'";
                $profilepic_run = mysqli_query($link, $profilepic);
                if ($profilepic_run && mysqli_num_rows($profilepic_run) > 0) {
                    $profie = mysqli_fetch_assoc($profilepic_run);

                    $profile_pic = $profie['profile_pic'];
                }


        ?>
                <img id="displayfriendprofile" src="<?php echo $profile_pic;  ?>" alt="profile image">
                <h2 id="viewprofilefromname"><?php echo  $Friends; ?></h2>

    </div>

<?php
                $msgs = "SELECT * FROM user_details WHERE UID = '$frdUID'";
                $sql_run = mysqli_query($link, $msgs);
                if ($sql_run && mysqli_num_rows($sql_run) > 0) {
                    $user = mysqli_fetch_assoc($sql_run);
                    $Friends = $user['username'];
                } else {
                    echo "Friend not found.";
                }
            }
        }
?>

<div id="bottom">
    <div id="profile">

    </div>
    <div id="allmsg">
        <table>

            <tbody>
                <?php
                $msg = "SELECT * FROM message WHERE (Sender = '$CurrentLoginUID' AND Receiver = '$frdUID') OR (Sender = '$frdUID' AND Receiver = '$CurrentLoginUID')";
                $msg_run = mysqli_query($link, $msg);
                if ($msg_run && mysqli_num_rows($msg_run) > 0) {
                    while ($messages = mysqli_fetch_assoc($msg_run)) {
                        $sender = $messages['Sender'];
                        $receiver = $messages['Receiver'];
                        $msg = $messages['msg'];
                        $time = $messages['time'];
                ?>
                        <div id="addbottom">

                            <?php
                            if ($receiver == $CurrentLoginUID) {
                                echo '<div id="receivedmessages">' . '<b>' . $msg . '</b>' . '<br>';
                                echo $time . '<br>' . '</div>' . '<br>';
                            } else {
                                echo '<div  id="sentmessaged">' . '<b>' . $msg . '</b>' . '<br>';
                                echo $time . '<br>' . '</div>' . '<br>';
                            }
                            ?>

                        </div>
                <?php
                    }
                } else {
                }
                ?>
            </tbody>
        </table>

    </div>

    <div id="takemsg">
        <form id="messageForm">
        <!-- <label for="file" class="file-label">
        <input type="file" id="file" style="display: none;" onchange="showFileName(this)">
        <span class="file-button" id="file-label">Choose File</span>
    </label> -->
            <input type="text" id="msg" name="msg" autocomplete="off" placeholder="Enter Your message..." required>
            <input type="button" id="btn" value="Send">
        </form>
    </div>
</div>
</div>
<script>
    document.getElementById('msg').addEventListener('keypress', function(event) {
        if (document.getElementById('msg').value !== '') {
            if (event.key === 'Enter') {
                document.getElementById('btn').click();
            }
        }

    });
    
</script>

<script>
    $(document).ready(function() {

        console.log("hii");

        var conn = new WebSocket('ws://localhost:8081');

        var userId = "<?php echo $CurrentLoginUID; ?>";
        var username = "<?php echo $CurrentLoginName; ?>";
        var receiver = "<?php echo $frdUID; ?>";

        conn.onopen = function(e) {
            console.log("Connection established!");
            conn.send(JSON.stringify({
                type: 'register',
                userId: userId,
                username: username,
                receiver: receiver
            }));
        };

        conn.onmessage = function(e) {
            var data = $.parseJSON(e.data);
            var name = data.name;
            var msg = data.msg;
            var sender = data.sender;
            var reciever = data.receiver;
            var time = new Date().toLocaleTimeString();

            var html = "<div id='addbottom'><div id='receivedmessages' ><b>" + msg + "</b><br>" + time + "</div></div><br/>";
            $('#allmsg').append(html);

        };

        $("#btn").click(function() {
            var msg = $('#msg').val();
            if (msg !== '') {


                var receiver = "<?php echo $frdUID; ?>";

                var content = {
                    type: 'message',
                    name: username,
                    msg: msg,
                    sender: userId,
                    receiver: receiver
                };
                var time = new Date().toLocaleTimeString();

                var html1 = "<div id='addbottom'><div id='sentmessaged'><b>" + msg + "</b><br>" + time + "</div></div><br/>";

                $('#allmsg').append(html1);

                conn.send(JSON.stringify(content));

                $.post('Twopersonchat.php', {
                    action: 'send_message',
                    name: username,
                    msg: msg,
                    sender: userId,
                    receiver: receiver
                }).done(function(response) {
                    console.log("Message sent and stored in the database.");
                }).fail(function() {
                    alert("Error sending message.");
                });

                $('#msg').val('');
            }
        });
    });
</script>


</html>