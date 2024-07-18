<?php
session_start();

if (isset($_GET['token'])) {
    if (file_exists('partials/db_connect.php')) {
        include 'partials/db_connect.php';
    } else {
        echo  "Database connection file not found.";
        exit; 
    }

    $identifier = mysqli_real_escape_string($link, $_GET['token']);

    $existsql = "SELECT * FROM user_details WHERE identifier='$identifier'";
    $result = mysqli_query($link, $existsql);
    $userdata = mysqli_fetch_array($result);

    if (!$result || !$userdata) {
        echo  "Invalid token or user not found.";
        exit; 
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pass = mysqli_real_escape_string($link, $_POST['password']);
        $cpass = mysqli_real_escape_string($link, $_POST['confirm_password']);
        
        if ($pass == $cpass) {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $updatequery = "UPDATE user_details SET password = '$hashed_password' WHERE identifier ='$identifier'";
            
            if (mysqli_query($link, $updatequery)) {
                
                $_SESSION['msg'] = "Your password has been updated successfully you can login now with your new password!!";
                echo json_encode(['status' => 'success', 'message' => 'Your password has been updated successfully. You can login now with your new password!']);
          
            } else {
                // echo "Error updating password: " . mysqli_error($link);
                echo json_encode(['status' => 'error', 'message' => 'Error updating password: ' . mysqli_error($link)]);

            }
        } else {
            // echo  "Passwords do not match";
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);

        }
        exit; 
    }
} else {
    echo "Token not provided.";

    exit; 
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
     

     body{
      background-color: #c9c8db;
    }
        .container {
           
      background-color:white;
    
          border:1px solid  #6a5acd;
          border-radius: 15px;
          height: 400px;
          width: 500px;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;
margin-top: 150px;
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

    <div class="container">
        <form class="row g-3" action="resetpassword.php" id="myForm"  method="POST">
            <h3>Reset Your Password</h3>
            <h6>Please enter your new password and confirm it below.</h6>
            <div class="col-12">
                <label for="password" >New Password</label>
                <input type="password" name="password" class="form-control" id="password" >
            </div>
            <div class="col-12">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" >
            </div>
            <div class="col-12">
                <button type="button"  id="btn" name="rstpsw" class="btn btn-primary mb-3">Update Password</button>
            </div>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>
 $(document).ready(function(){
    $('#btn').click(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'resetpassword.php?token=<?php echo $_GET['token']; ?>',
            data: $('#myForm').serialize(),
            dataType: 'json', 
            success: function(response){
                if (response.status === 'success') {
                    window.location.href = 'Userloginpage.php';
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error){
                console.error('Error:', error);
            }
        });
    });
});

</script>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>