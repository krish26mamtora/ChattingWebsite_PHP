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
}

$FetchReceivedUID = "SELECT Recieved FROM user_connections WHERE UID = '$CurrentLoginUID'";
$result = mysqli_query($link, $FetchReceivedUID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Friend Requests</title>
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
            background-color: #6a5acd;
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
            background-color: #a6a1e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #Accept{
            background-color: #7BE53B;
        }
        #Reject{
            background-color: #FC7858;
        }
        button:disabled {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Received Friend Requests</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Accept</th>
            <th>Reject</th>
        </tr>
        <?php
        if ($result) {
            while ($FRReceivedUID = mysqli_fetch_assoc($result)) {
                $allReceivedUID = $FRReceivedUID['Recieved'];
                $numbersArray = explode(" ", $allReceivedUID);

                foreach ($numbersArray as $number) {
                    $number = trim($number);
                    if (!empty($number)) {
                        $FetchReceivedEmail = "SELECT email FROM user_details WHERE UID = '$number'";
                        $FetchReceivedEmail_run = mysqli_query($link, $FetchReceivedEmail);
                        if ($FetchReceivedEmail_run && mysqli_num_rows($FetchReceivedEmail_run) > 0) {
                            while ($row = mysqli_fetch_assoc($FetchReceivedEmail_run)) {
                                ?>
     
                                <tr>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <form action="AcceptorRejectFR.php" method="POST">
                                            <input type="hidden" name="ReceivedMailFrom" value="<?php echo htmlspecialchars($row['email']); ?>">
                                            <input type="hidden" name="ReceivedUID" value="<?php echo htmlspecialchars($number); ?>">
                                            <input type="hidden" name="currentUID" value="<?php echo htmlspecialchars($CurrentLoginUID); ?>">
                                            <button name="Accept" id="Accept" type="submit">Accept</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="AcceptorRejectFR.php" method="POST">
                                            <input type="hidden" name="ReceivedMailFrom" value="<?php echo htmlspecialchars($row['email']); ?>">
                                            <input type="hidden" name="ReceivedUID" value="<?php echo htmlspecialchars($number); ?>">
                                            <input type="hidden" name="currentUID" value="<?php echo htmlspecialchars($CurrentLoginUID); ?>">
                                            <button name="reject" id="Reject" type="submit">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
            }
        } else {
            echo "<tr><td colspan='3'>No friend requests found.</td></tr>";
        }
        mysqli_close($link);
        ?>
    </table>
</body>
</html>
