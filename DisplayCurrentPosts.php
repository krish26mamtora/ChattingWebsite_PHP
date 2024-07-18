<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet"  href="style_YourPosts.css">
</head>
<body>
    
<section id="posts">
<!-- <div class="post-container"> -->
<?php
session_start();
if (file_exists('partials/db_connect.php')) {
  include 'partials/db_connect.php';
} else {
  echo "connection file not found.";
}
// $email = $_SESSION['email'];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $CurrentLoginUID = $_POST['currentlogin'];
    $query = 'SELECT posts.uname, posts.title, posts.PID,posts.media, posts.content,posts.date,user_profile.profile_pic
    FROM posts
    JOIN user_profile ON posts.UID = user_profile.UID
    WHERE posts.UID = "' . $CurrentLoginUID . '"';

  $query_run = mysqli_query($link, $query);
  if ($query_run && mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
    //   echo '<div class="post">';
    //   echo '<div id="username">';
    //   echo '<img id="profimg" src="' .  $row['profile_pic'] . '" alt="profpic" >';
    //   echo $row['uname'] .'</div>';
   
    //   echo '<div id="media">';
    //   echo '<img id="postimg" src="' . $row["media"] . '" alt="postpic"></div>';
    //   echo '<div id="content">';
    // //   echo '<i class="bi bi-heart text-danger me-3" id="heart-icon"></i>';
    //   echo  '<b>'.$row['title'] .'</b> '.'  '. $row['content'] . '</div>';
    //    echo '<div id="date">';
    //   echo $row['date'] . '</div></div><br>';
    echo '<div class="post">';
            echo '<div id="username">';
            echo '<img id="profimg" src="' .  $row['profile_pic'] . '" alt="profpic" >';
            echo $row['uname'] . ' </div>';

            echo '<div id="media">';
            echo '<img id="postimg" src="' . $row["media"] . '" alt="postpic"></div>';
            echo '<div id="content">';
            echo '<div><i class="bi bi-heart text-danger me-3" id="heart-icon"></i>';
            echo '<b>' . $row['title'] . '</b> ' . $row['content'] . '</div>';
            echo '<b><a onclick="togglecomments(\'' . $row["PID"] . '\')" id="comments">comments</a></b></div>';
            echo '<div id="date">';
            echo $row['date'] . '</div></div><br>';
    }
  }

}
?>
<!-- </div> -->
</section>

</body>
</html>