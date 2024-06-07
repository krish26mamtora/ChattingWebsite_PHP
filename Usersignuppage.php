<?php
if (file_exists('partials/nav.php')) {
  include 'partials/nav.php';
} else {
  echo "Navigation file not found.";
}

if (isset($_POST['register'])) {
  if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
  } else {
    echo "connection file not found.";
  }
  $email = ($_POST['email']);
  $pass = ($_POST['password']);
  $cpass = ($_POST['confirm_password']);

  $existsql = "SELECT * FROM user_details WHERE email='$email'";
  $result = mysqli_query($link, $existsql);
  $numExistRows = mysqli_num_rows($result);

  if ($numExistRows > 0) {
    echo '
    <div class="alert alert-secondary alert-dismissible fade show" role="alert" style="margin-top:5px;">
      <strong>Email already exists!</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
  } else {
    if ($pass == $cpass) {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $_SESSION['email'] = $email;

      $random_num = rand(1000, 9999);
      $identifier = password_hash($random_num, PASSWORD_DEFAULT);

      $sql = "INSERT INTO user_details (identifier, email, password) VALUES ('$identifier', '$email', '$hash')";
      $result = mysqli_query($link, $sql);
        
      $connection = "INSERT INTO user_connections (UID) VALUES ('')";
      $InsertIntoConnections = mysqli_query($link, $connection);

      $userprofile = "INSERT INTO user_profile (UID) VALUES ('')";
      $InderttoProfile = mysqli_query($link, $userprofile);

      if ($result) {
        $to = $email;
        $subject = 'E-mail verification';
        $f2 = "http://localhost/Assignments/Userloginpage.php?token=$identifier";

        echo $f2;
        $message = "
        <html>
        <head>
          <title>E-mail verification</title>
        </head>
        <body>
          <p>Your account has been created. You can log in now by clicking on this link:</p>
          <a href='$f2'>Verify your email address</a>
         
        </body>
        </html>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: krishmamtora26@gmail.com\r\n";

        $mail = mail($to, $subject, $message, $headers);

        if ($mail) {
          echo 'Email sent successfully.';
        } else {
          echo 'Email sending failed.';
        }
      }

      header('Location:EmailVarification.php');
      exit();
    } else {
      echo '
      <div class="alert alert-secondary alert-dismissible fade show" role="alert" style="margin-top:5px;">
        <strong>Passwords do not match!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="style_signup.css">
  <style>
    * {
      font-family: Arial, Helvetica, sans-serif;
    }

    button:hover {
      transform: scale(1.1);
    }

    .btn {
      color: white;
    }

    #maindiv {
      display: flex;
      flex-direction: row;
      height: 490px;
      margin-top: 100px;
      width: 730px;
      border: 1px solid #a6a1e0;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      padding: 20px;
      border-radius: 25px;
      align-items: center;
      justify-content: space-between;
    }
#regbtn{
  background-color: slateblue;
}
    #left {
      height: 100%;
      width: 50%;
    }

    #signupform {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    #right {
      background-color: slateblue;
      height: 100%;
      width: 50%;
      border-radius: 15px;
      color: black;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    #right h1 {
      color: white;
    }
  </style>
</head>

<body>
  <div class="container" id="maindiv">
    <div class="container" id="left">
      <form class="my-1" id="signupform" action="Usersignuppage.php" method="POST">
        <div class="mb-3">
          <h3 style="text-align:center;">Create Account</h3>
        
        </div>
        <div class="mb-3">
          <label for="InputEmail" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="mb-3">
          <label for="confirmpassword" class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" id="confirmpassword" required>
          <div id="emailHelp" class="form-text">Make sure you enter the same password</div>
        </div>
        <div class="d-flex justify-content-center">
          <button type="submit" name="register" class="btn" id="regbtn">Register</button>
        </div>
      </form>
    </div>
    <div class="container" id="right">
      <h1>Welcome!</h1>
      <p>Sign up to join our community.</p>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
