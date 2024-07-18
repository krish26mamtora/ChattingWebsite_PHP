<?php
session_start();
if (file_exists('partials/db_connect.php')) {
  include 'partials/db_connect.php';
} else {
  echo "connection file not found.";
}
$email = $_SESSION['email'];
$UIDofCurrentLoginUser = "SELECT UID,username FROM user_details WHERE email = '$email'";
$result = mysqli_query($link, $UIDofCurrentLoginUser);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $CurrentLoginUID = $row['UID'];
  $CurrentLoginname = $row['username'];
}
$showpost = [];


function cheackforfriend($UIDtocheck, $CurrentLoginUID)
{
  global $link;
  $sql = "SELECT Friends FROM user_connections WHERE UID = $CurrentLoginUID";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $AllFriends = $row['Friends'];
      if (strstr($AllFriends, $UIDtocheck)) {
        return '';
      } else {
        return '<i class="bi bi-person-plus me-2" onclick="asktoaddfriend(\'' . $UIDtocheck . '\', \'' . $CurrentLoginUID . '\')" id="addfriend"></i>';
      }
    }
  }
}


if($_SERVER['REQUEST_METHOD']=='POST'){
    $tosearch = $_POST['tosearch'];
    if($tosearch && $tosearch!==''){
    $display = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
      $display_run = mysqli_query($link, $display);
      if ($display_run && mysqli_num_rows($display_run) > 0) {
        $user = mysqli_fetch_assoc($display_run);
        $Friends = $user['Friends'];
        $friendsArray = explode(' ', $Friends);
        foreach ($friendsArray as $friendUID) {
          $friendUID = trim($friendUID);
          $FetchingData = "SELECT * From posts WHERE UID = '$friendUID'";
          $EmailofFriends_run = mysqli_query($link, $FetchingData);
          if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
            while ($fetchedata = mysqli_fetch_assoc($EmailofFriends_run)) {   
              if( ($fetchedata['title'] && $fetchedata['title']==$tosearch)||($fetchedata['uname']==$tosearch)){
                  array_push($showpost, $fetchedata['PID']);     
              }
            }
          }
        }
      }      
      $publicusers = "SELECT UID FROM user_details WHERE type = 'public' ";
      $publicusers_run = mysqli_query($link, $publicusers);
      if ($publicusers_run && mysqli_num_rows($publicusers_run) > 0) {
        while ( $publicuser_PID = mysqli_fetch_assoc($publicusers_run)){
        $public_UIDs = $publicuser_PID['UID'];
        if ($public_UIDs == $CurrentLoginUID) {   
        } else
         {
          $FetchingPID = "SELECT * From posts WHERE UID = '$public_UIDs'";
          $FetchingPID_run = mysqli_query($link, $FetchingPID);
          if ($FetchingPID_run && mysqli_num_rows($FetchingPID_run) > 0) {
            while ($fetchPIDs = mysqli_fetch_assoc($FetchingPID_run)) {
                if(($fetchPIDs['title'] && $fetchPIDs['title']==$tosearch)||($fetchPIDs['uname']==$tosearch)){
              array_push($showpost, $fetchPIDs['PID']);
                } 
            }
          }
        }}
      }

   
    }else{
        $display = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
      $display_run = mysqli_query($link, $display);
      if ($display_run && mysqli_num_rows($display_run) > 0) {
        $user = mysqli_fetch_assoc($display_run);
        $Friends = $user['Friends'];
        $friendsArray = explode(' ', $Friends);
        foreach ($friendsArray as $friendUID) {
          $friendUID = trim($friendUID);
          $FetchingData = "SELECT * From posts WHERE UID = '$friendUID'";
          $EmailofFriends_run = mysqli_query($link, $FetchingData);
          if ($EmailofFriends_run && mysqli_num_rows($EmailofFriends_run) > 0) {
            while ($fetchedata = mysqli_fetch_assoc($EmailofFriends_run)) {   
                  array_push($showpost, $fetchedata['PID']);     
            }
          }
        }
      }      
      $publicusers = "SELECT UID FROM user_details WHERE type = 'public' ";
      $publicusers_run = mysqli_query($link, $publicusers);
      if ($publicusers_run && mysqli_num_rows($publicusers_run) > 0) {
        while ( $publicuser_PID = mysqli_fetch_assoc($publicusers_run)){
        $public_UIDs = $publicuser_PID['UID'];
        if ($public_UIDs == $CurrentLoginUID) {   
        } else
         {
          $FetchingPID = "SELECT * From posts WHERE UID = '$public_UIDs'";
          $FetchingPID_run = mysqli_query($link, $FetchingPID);
          if ($FetchingPID_run && mysqli_num_rows($FetchingPID_run) > 0) {
            while ($fetchPIDs = mysqli_fetch_assoc($FetchingPID_run)) {
             
              array_push($showpost, $fetchPIDs['PID']);
                
            }
          }
        }}
      }
    }

      $showpost = array_unique($showpost);
   
      shuffle($showpost);

      if(empty($showpost)){
        echo 'No Posts Found!';
      }else{
      foreach ($showpost as $post) {

        $query = 'SELECT posts.uname, posts.title, posts.media,posts.PID,posts.UID, posts.content,posts.date,user_profile.profile_pic
          FROM posts
          JOIN user_profile ON posts.UID = user_profile.UID
          WHERE posts.PID = "' . $post . '"';

        $query_run = mysqli_query($link, $query);
        if ($query_run && mysqli_num_rows($query_run) > 0) {
          while ($row = mysqli_fetch_assoc($query_run)) {
            echo '<div class="post">';
            echo '<div id="username">';
            echo '<img id="profimg" src="' .  $row['profile_pic'] . '" alt="profpic" >';
            echo $row['uname'];
            $alreadyfriend = cheackforfriend($row['UID'], $CurrentLoginUID);
            echo $alreadyfriend;
            echo '</div>';

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
    }
 
      

}
?>