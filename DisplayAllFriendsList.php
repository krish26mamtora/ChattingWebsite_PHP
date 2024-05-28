<?php
session_start();
if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
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

function RemoveUIDfromFriend($currentUID, $FriendUID) {
    $link = mysqli_connect("localhost", "root", "", "chattingapp");
    $sql = "SELECT * FROM user_connections WHERE UID= '" . $currentUID . "'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $AllFriends = $row['Friends'];
            $RemoveFromFriend = str_replace($FriendUID, "", $AllFriends);
            $deletefromFriend = 'UPDATE user_connections SET Friends = "' . $RemoveFromFriend . '" WHERE UID="' . $currentUID . '"';
            $deletefromsent_run = mysqli_query($link, $deletefromFriend);
            if ($deletefromsent_run) {
                echo "Removed From Friend";
            } else {
                echo "Error updating friend list.";
            }
        }
    } else {
        echo "No matching records found.";
    }
}

if (isset($_POST['RemoveFriend'])) {
    $CurrentLoginUID = $_POST['CurrentLoginUID'];
    $FriendUID = $_POST['FriendUID'];
    RemoveUIDfromFriend($CurrentLoginUID, $FriendUID);
    RemoveUIDfromFriend($FriendUID, $CurrentLoginUID);
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        table th {
            background-color: #a6a1e0;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f1f3f5;
        }
        table tr:hover {
            background-color: #e9ecef;
        }
        button {
            padding: 8px 12px;
            font-size: 14px;
            color: white;
            background-color: #FC7858;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:disabled {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Your Friends</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $Friends = $row['Friends'];

            $friendsArray = explode(' ', $Friends);
            foreach ($friendsArray as $friendUID) {
                $friendUID = trim($friendUID);

                $EmailofFriends = "SELECT * FROM user_details WHERE UID = '$friendUID'";
                $EmailofFriends_run = mysqli_query($link, $EmailofFriends);

                if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
                    $fetchemail = mysqli_fetch_assoc($EmailofFriends_run);
                    $FriendsEmail = $fetchemail['email'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($FriendsEmail); ?></td>
                        <td>
                            <form action="DisplayAllFriendsList.php" method="POST">
                                <input type="hidden" name="FriendUID" value="<?php echo htmlspecialchars($friendUID); ?>">
                                <input type="hidden" name="CurrentLoginUID" value="<?php echo htmlspecialchars($CurrentLoginUID); ?>">
                                <button type="submit" name="RemoveFriend">Remove Friend</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td colspan="2">No friends found</td>
                    </tr>
                    <?php
                }
            }
        } else {
            ?>
            <tr>
                <td colspan="2">No friends found for the current user.</td>
            </tr>
            <?php
        }
        mysqli_close($link);
        ?>
    </table>
</body>
</html>
