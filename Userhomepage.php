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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage Title</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="styles_postpage.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="Userhomepage.js"></script>
  <style>
    .content {
    margin-left: 240px;
    padding: 20px;
}
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <h3>Posts</h3>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <button onclick="toggleModal()" id="addbtn">Add Post</button>
          <form action="Userhomepage.php" id="ypform">
            <input type="text" name="currentlogin" value="<?php echo $CurrentLoginUID; ?>" hidden>
            <button id="YourPostsHP">Your Posts</button>
          </form>

          <form class="d-flex fd-r" id="searchform" mathod="POST">
            <input class="form-control me-2" type="search" name="tosearch" placeholder="Enter username/title" aria-label="Search">
            <button class="btn btn-outline-success" type="button" name="searchpost" id="searchpost">Search</button>
          </form>
        </div>
      </div>
    </nav>
  </header>
  <hr>
  <section id="posts">
    <div class="post-container" id="searchresponse">
      <?php


      $display = "SELECT * FROM user_connections WHERE UID = '$CurrentLoginUID'";
      $display_run = mysqli_query($link, $display);
      if ($display_run && mysqli_num_rows($display_run) > 0) {
        $user = mysqli_fetch_assoc($display_run);
        $Friends = $user['Friends'];

        $friendsArray = explode(' ', $Friends);
        foreach ($friendsArray as $friendUID) {
          $friendUID = trim($friendUID);
          $FetchingData = "SELECT PID From posts WHERE UID = '$friendUID'";

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
        while ($publicuser_PID = mysqli_fetch_assoc($publicusers_run)) {
          $public_UIDs = $publicuser_PID['UID'];
          if ($public_UIDs == $CurrentLoginUID) {
          } else {
            $FetchingPID = "SELECT PID From posts WHERE UID = '$public_UIDs'";

            $FetchingPID_run = mysqli_query($link, $FetchingPID);

            if ($FetchingPID_run && mysqli_num_rows($FetchingPID_run) > 0) {
              while ($fetchPIDs = mysqli_fetch_assoc($FetchingPID_run)) {
                array_push($showpost, $fetchPIDs['PID']);
              }
            }
          }
        }
      }



      $showpost = array_unique($showpost);

      shuffle($showpost);

      foreach ($showpost as $post) {

        $query = 'SELECT posts.uname, posts.title,posts.cmts, posts.media,posts.PID,posts.UID, posts.content,posts.date,user_profile.profile_pic
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
            $msg_array = json_decode($row['cmts'], true);

            echo '<div id="media">';
            echo '<img id="postimg" src="' . $row["media"] . '" alt="postpic"></div>';
            echo '<div id="content">';
            echo '<div><i class="fa fa-heart" id="likebtn" onclick="like()" style="font-size: 20px; color: black; margin-right:10px; margin-top:4px;"></i>';
            echo '<b><a onclick="togglecomments(\'' . $row["PID"] . '\')" id="comments"><i class="bi bi-chat-dots me-2 ml-n3"></i></a></b></div>';
            echo '<b>' . $row['title'] . '</b> ' . $row['content'] . '</div>';
            echo '<div id="date">';
            echo $row['date'] . '</div></div><br>';
          }
        }
      }
      ?>


    </div>

  </section>
  <div class="modal-bg" id="modalBg">
    <div class="leftspace"></div>
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <div class="ctnttl">
        <h2>Add New Post</h2>
      </div>

      <form id="postdata" method="POST" enctype="multipart/form-data">
        <div class="mb-3" id="displaymsg">

        </div>
        <div class="mb-3 ">
          <label for="postTitle" class="form-label">Title</label>
          <input type="text" class="form-control" name="postTitle" id="postTitle" placeholder="Enter title">
        </div>
        <div class="mb-3">
          <label for="postContent" class="form-label">About</label>
          <textarea class="form-control" name="postContent" id="postContent" rows="3" placeholder="About Your Post..."></textarea>
        </div>
        <div class="mb-3">
          <input type="file" id="postpic" required name="postpic" accept="image/*">
        </div>
        <div class="ctnbtn">
          <button type="submit" id="addpostbtn" name="submitbtn" class="btn btn-primary">Add Post</button>
        </div>
      </form>
    </div>
    <div class="rightspace"></div>
  </div>


  <div class="modal-bg" id="modalcomment">
    <div class="leftspace"></div>
    <div class="modal-content" id="modal-comments">
      <span class="close-btn" onclick="closecomments()">&times;</span>
      <div class="ctnttl">
        <h2>Comments</h2>
      </div>
      <div id="allcomments">

        <div id="comment">

        </div>
      </div>

      <form action="Userhomepage.php" id="commenttdata" method="POST">

        <input type="text" id="currentPID" name="currentPID" hidden>

        <div class="row" id="cmts">
          <div class="col-sm-8">
            <input type="text" class="form-control" name="commentvalue" id="commentvalue" placeholder="Comment here.." required>
          </div>
          <div class="col-sm-4">
            <input type="text" value="<?php ?>" hidden>
            <button type="button" id="addcomment" name="addcomment" class="btn btn-primary">Add</button>
          </div>
        </div>
      </form>

    </div>
    <div class="rightspace"></div>
  </div>



  <div class="modal-bg" id="modalprofile">
    <div class="leftspace"></div>
    <div class="modal-content" id="modal-comments">
      <span class="close-btn" onclick="closeprofile()">&times;</span>
      <div class="ctnttl">
        <h2>Profile</h2>
      </div>
      <div id="displaypostprofile">

      </div>


    </div>
    <div class="rightspace"></div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script>


function SendFriendRequest( otherUserUID, CurrentLoginUID, currentUserEmail) {
          $.ajax({
          type: 'POST',
          url: 'SendRequest.php',
          data: {
              'UID': otherUserUID,
              'CurrentLoginUID': CurrentLoginUID,
              'currentuser': currentUserEmail
          },
          success: function(response) {
              alert("Friend request has been sent successfully!!");
              var modalBg = document.getElementById('modalprofile');
                modalBg.style.display = 'none';
          },
          error: function(response) {
              alert("error occured");

          }
      });
  }



</script>


</body>


</html>