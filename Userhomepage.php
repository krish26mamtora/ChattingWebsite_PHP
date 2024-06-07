<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>

<?php



// if ($_SESSION['loggedin'] = true) {
//   echo '
//     <div class="alert alert-secondary alert-dismissible fade show" role="alert" Style="margin-top:5px;">
//       <strong>Welcome!!</strong>
//       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//     </div>
//     ';
// }


// if (isset($_POST['Delete_Account'])) {

//   if (file_exists('partials/db_connect.php')) {
//     include 'partials/db_connect.php';
//   } else {
//     echo "connection file not found.";
//   }

//   $email = $_SESSION['email'];
//   echo $email;
//   $sql = "DELETE FROM `user_details` WHERE `email` = '$email'";
//   $result = mysqli_query($link, $sql);
//   echo "account deleted";
//   session_unset();
//   session_destroy();
//   header("location:Homepage.php");
// }
?>
<!-- <h1>hello</h1>
<button id="sendFD">Send Request</button>
<script>
  document.getElementById('sendFD').addEventListener('click', function() {
    // window.location.href = 'DisplayAllUsersWhileAdding.php';
    window.location.href = 'try.php';

  });
</script>
<br><br>
<button id="ReceivedFR">Recieved Request</button>
<script>
  document.getElementById('ReceivedFR').addEventListener('click', function() {
    window.location.href = 'DisplayReceivedFR.php';
  });
</script>
<br><br>
<button id="logout">Logout</button>
<script>
  document.getElementById('logout').addEventListener('click', function() {
    window.location.href = 'logout.php';
  });
</script>
<br><br>
<button id="Friends">Friends</button>
<script>
  document.getElementById('Friends').addEventListener('click', function() {
    window.location.href = 'DisplayAllFriendsList.php';
  });
</script>
<br><br>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_account">
  Delete Account
</button>

<div class="modal fade" id="delete_account" tabindex="-1" aria-labelledby="delete_accountLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="delete_accountLabel">Delete Account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete your Account???
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <form action="Userhomepage.php" method=POST>
          <button type="submit" name="Delete_Account" class="btn btn-danger">Delete</button>

        </form>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>  -->
