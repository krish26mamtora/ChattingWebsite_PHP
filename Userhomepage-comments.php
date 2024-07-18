<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #comment {
            background-color: white;
            padding: 1px;
            margin-bottom: 5px;
            border-radius: 10px;
        }

        .onecomment {
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $CurrentPostID = $_POST['PID'];

        $sql = "SELECT cmts FROM posts WHERE PID = $CurrentPostID";
        $sql_run = mysqli_query($link, $sql);

        if ($sql_run && mysqli_num_rows($sql_run) > 0) {
            $row = mysqli_fetch_assoc($sql_run);
            $cmts_json = $row['cmts'];

            $comments = json_decode($cmts_json, true);
         
            if ($comments === null) {
                
            } else {
                foreach ($comments as $comment) {
                    if (isset($comment['sender']) && isset($comment['comment'])) {
                        echo '<div id="comment">';
                        echo '<div class="onecomment"><b>' . $comment['sender'] . '</b>: ' . $comment['comment'] . '</div>';
                        echo '</div>';
                    } else {
                        echo "Missing 'sender' or 'comment' key in comment data.";
                    }
                }
            }
        } else {
            echo "No comments found for Post ID: $CurrentPostID.";
        }
    }

    // Close the database connection
    mysqli_close($link);
    ?>
</body>

</html>