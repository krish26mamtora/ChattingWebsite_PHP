<?php  

// if (file_exists('partials/nav.php')) {
//   include 'partials/nav.php';
// } else {
//   echo "Navigation file not found.";
// }
session_start();
if (isset($_POST['rstpswmail'])) {

  if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
  } else {
    echo "connection file not found.";
  }
  $email = ($_POST['email']);


  $existsql = "SELECT * FROM user_details WHERE email='$email'";
  $result = mysqli_query($link, $existsql);
  $numExistRows = mysqli_num_rows($result);

  if ($numExistRows > 0) {
    
    $userdata = mysqli_fetch_array($result);
    $username = $userdata['username'];
    $identifier = $userdata['identifier'];
        $to = $email;
        $subject = 'Password Reset';
        $f2 = "http://localhost/Assignments/resetpassword.php?token=$identifier";

        echo $f2;
        $message = "
        <html>
        <head>
          <title>Password Reset</title>
        </head>
        <body>
          <p>Click here to reset your password:</p>
          <a href='$f2'>Update Your Password</a>
         
        </body>
        </html>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: krishmamtora26@gmail.com\r\n";

        $mail = mail($to, $subject, $message, $headers);

        if ($mail) {
          
          $_SESSION['msg']  ="Update Password email has been sent to $to pease check you email";
          header('location:Userloginpage.php');
        } else {
          $_SESSION['msg']= 'Email sending failed.';
          header('location:Userloginpage.php');

        }
      
  }else{
    echo "no email found!";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
     

    body{
      background-color: #c9c8db;
    }
        .container {
          border:1px solid  #6a5acd;
          border-radius: 15px;
          height: 400px;
          width: 500px;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;
margin-top: 150px;
background-color: white;
        }
        form {
         
            max-width: 400px;
            width: 100%;
        }
      
       button{
        width: 100%;
       }
    </style>
</head>
<body>
<div id="main">
<div class="container">
    <form class="row g-3" method="POST" action="ForgotPassword.php">
        <h3>Reset Password</h3>
        <h6>Please enter your email address below to receive instructions on how to reset your password.</h6>
        <div class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="col-12">
            <button type="submit" name="rstpswmail" class="btn btn-primary">Submit</button>
        </div>
    </form>
    
    <form class="row g-3" action="Userloginpage.php">
    <div class="col-12 mt-4">
      <button type="submit" id="back" class="btn btn-secondary">Back</button></div>
    </form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>

</head>

</html>