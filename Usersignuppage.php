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
    $_SESSION['alertsignup'] = 'Email already exists!';
  } else {
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $pass)) {
      $_SESSION['alertsignup'] = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
  } 
  else{
    if ($pass == $cpass) {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $_SESSION['email'] = $email;

      $random_num = rand(1000, 9999);
      $identifier = password_hash($random_num, PASSWORD_DEFAULT);

      $data = ['identifier' => $identifier, 'email' => $email, 'password' => $hash];
      $result = insert('user_details', $data);

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


        echo '<script>
        document.getElementById("regbtn").disabled = true;
        document.getElementById("spinner").style.display = "inline-block";
        </script>';

        if ($mail) {
          echo 'Email sent successfully.';
        } else {
          echo 'Email sending failed.';
        }
      }

      echo '<script>
      document.getElementById("spinner").style.display = "none";
      document.getElementById("regbtn").disabled = false;
      </script>';


      header('Location:EmailVarification.php');
      exit();
    } else {
      $_SESSION['alertsignup'] = 'password not match';
    }
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <style>
    #spinner {
      display: none;
    }
    .input-group {
    position: relative;
  }

  #togglePassword {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
  }
  </style>
</head>

<body>

  <div id="dispmsg">
    <?php
    if (isset($_SESSION['alertsignup']) && !empty($_SESSION['alertsignup'])) {
      echo $_SESSION['alertsignup'];
    } else {
      // echo "No message available.";
    }
    ?>

  </div>
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
        <!-- <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div> -->
        <div class="mb-3">
  <label for="password" class="form-label">Password</label>
  <div class="input-group">
    <input type="password" name="password" class="form-control" id="password" required>
    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
    <i class="fa fa-eye" style="font-size:20px"></i>
    </button>
  </div>
</div>

        <!-- <div class="mb-3">
          <label for="confirmpassword" class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" id="confirmpassword" required>
          <div id="emailHelp" class="form-text">Make sure you enter the same password</div>
        </div> -->
        <div class="mb-3">
  <label for="confirmpassword" class="form-label">Confirm Password</label>
  <div class="input-group">
    <input type="password" name="confirm_password" class="form-control" id="confirmpassword" required>
    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
      <i class="fa fa-eye" style="font-size:20px" id="confirmEyeIcon"></i>
    </button>
  </div>
  <div id="emailHelp" class="form-text">Make sure you enter the same password</div>
</div>

        <div class="mb-4">
          <label for="login" class="form-label">Already have account?</label>
          <a href="Userloginpage.php" id="login" name="login" class="alert-link" style="color: slateblue;">login</a>
        </div>
        <div class="d-flex justify-content-center">
          <button type="submit" name="register" class="btn" id="regbtn">Register</button>
          <div id="spinner" class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </form>
    </div>
    <div class="container" id="right">
      <img src="registrerimg.jpg" alt="logo" style="  margin-bottom: 20px;">
      <h1>Welcome!</h1>
      <p style="color:white;">Sign up to join our community.</p>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
  
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#confirmpassword');

    toggleConfirmPassword.addEventListener('click', function() {
      const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confirmPassword.setAttribute('type', type);
    });
  });
</script>

</body>

</html>