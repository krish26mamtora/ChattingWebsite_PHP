<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #onecomment{
            background-color: rgb(234, 234, 234);
            height: auto;
            width: auto;
            margin-bottom: 1px;
            padding: 3px;
            border-radius: 5px 5px 5px 0;
            display: inline-block;
            max-width: 80%;
            padding: 10px;
        }
    </style>
</head>
<body>
    
    <?php

session_start();

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "Connection file not found.";
    exit;
}

$email = $_SESSION['email'];
       $findusername =  "SELECT username FROM user_details WHERE email = '$email'";
       $findusername_run = mysqli_query($link, $findusername);
       if ($findusername_run && mysqli_num_rows($findusername_run) > 0) {

       while ($fetchusername = mysqli_fetch_assoc($findusername_run)) {
        $currentusername=$fetchusername['username'];
       }}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commentdata = $_POST['commentvalue'];
    $currentPID = $_POST['currentPID'];

    if($commentdata!=='' && !is_null($commentdata)){
        $select = "SELECT cmts FROM posts WHERE PID = $currentPID";
        $select_run = mysqli_query($link, $select);
    
        if ($select_run && mysqli_num_rows($select_run) > 0) {
            $row = mysqli_fetch_assoc($select_run);
            $current_cmts_json = $row['cmts'];
    
            $current_cmts = json_decode($current_cmts_json, true) ?: [];
            
            $new_comment = [
                'sender' => $currentusername,
                'comment' => $commentdata
            ];
    
            $current_cmts[] = $new_comment;
    
            $updated_cmts_json = json_encode($current_cmts);
    
            $update = "UPDATE posts SET cmts = '$updated_cmts_json' WHERE PID = $currentPID";
            $update_run = mysqli_query($link, $update);
    
            if ($update_run) {
                echo '<div id="comment">';
                echo '<div id="onecomment">';
                echo '<b>' . $currentusername . '</b>' . ' : ' . $commentdata . '</div>';
                echo '</div>';
            } else {
                echo "Error updating comments.";
            }
        } else {
            echo "No posts found with PID $currentPID.";
        }
    }
}

mysqli_close($link);

?>

</body>
</html>