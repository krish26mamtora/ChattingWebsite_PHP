<?php

if (file_exists('partials/nav.php')) {
    include 'partials/nav.php';
} else {
    echo "Navigation file not found.";
}

if (file_exists('partials/db_connect.php')) {
    include 'partials/db_connect.php';
} else {
    echo "connection file not found.";
}

if (isset($_GET['token'])) {
    $identifier = ($_GET['token']);
    $sql_verify_query = "SELECT email, identifier, varified, password FROM user_details WHERE identifier='$identifier' LIMIT 1";
    $sql = mysqli_query($link, $sql_verify_query);

    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql);
        if ($row['varified'] == '0') {

            if (isset($_POST['password']) && isset($_POST['email']) && isset($_POST['verify_token'])) {
                $email = $_POST['email'];
                $pass = $_POST['password'];

                if ($email === $row['email']) {
                    if (password_verify($pass, $row['password'])) {
                        $update_status = "UPDATE user_details SET varified='1' WHERE identifier='$identifier' LIMIT 1";
                        $update_status_run = mysqli_query($link, $update_status);
                        if ($update_status_run) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['email'] = $email;
                            if(isset($_POST['rememberme'])){
                                setcookie('emailcookie',$email,time()+86400);
                                setcookie('passwordcookie',$pass,time()+86400);
    
                            header('location:ProfileDetails.php');
                            exit();
                        }
                            else{
                                header('location:ProfileDetails.php');
                                exit();
                            }
                        }
                    } else {
                        //       echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
                        // <strong>Please enter a valid password</strong> 
                        // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        // </div>
                        // ';
                        // echo 'Please enter a valid password';
                        $_SESSION['msg']="Please enter a valid password";

                    }
                } else {
                    // echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
                    //     <strong>Please enter valid Email-id</strong> 
                    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    //     </div>
                    //     ';
                    $_SESSION['msg']="Please enter valid Email-id";

                }
            }
        } else {
            // echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
            //             <strong>Account is already varified</strong> 
            //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            //             </div>
            //             ';
            $_SESSION['msg']="Account is already varified";

        }
    } else {
        // echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
        //                 <strong>Invalid token</strong> 
        //                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        //                 </div>
        //                 ';
        $_SESSION['msg']="Invalid token";

    }
} else {
    $_SESSION['loggedin'] = false;
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $sql = "SELECT * FROM `user_details` WHERE email='$email'";
        $result = mysqli_query($link, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['varified'] == '1') {
                    if (password_verify($pass, $row['password'])) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['authenticated'] = 'true';
                        if(isset($_POST['rememberme'])){
                            setcookie('emailcookie',$email,time()+86400);
                            setcookie('passwordcookie',$pass,time()+86400);

                            header('location:Sidebar.php');
                            exit();

                        }else{
                            header('location:Sidebar.php');
                            exit();

                        }
                    } else {
                        // echo '<div class="d-flex justify-content-center">

                        // <div class="alert alert-danger alert-dismissible fade show mt-4 w-75" role="alert">
                        // <strong>Please enter a valid password</strong> 
                        // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        // </div> </div>
                        // ';
                        $_SESSION['msg']="Please enter a valid password";
                    }
                } else {
                    // echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
                    //     <strong>Please varify your account</strong> 
                    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    //     </div>
                    //     ';
                    $_SESSION['msg']="Please varify your account";

                }
            }
        } else {
            // echo '<div id="alertmsg" class="alert alert-danger alert-dismissible fade show" role="alert" >
            // <strong>No user found with this emial id</strong> 
            // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            // </div>
            // ';
            $_SESSION['msg']="No user found with this emial id";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Verify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style_login.css">
</head>

<body>

    <div id="dispmsg">
    <?php 
if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
    echo $_SESSION['msg'];
} else {
    // echo "No message available.";
}
?>

    </div>
    <div class="container" id="maindiv">
        
        <div class="container" id="left">
            <img src="loginimg.jpg" alt="loginimg">
            <br>
            <h3>Be Verified</h3>
            <h6>"Connect in a Real and Meaningful Way!"</h6>
        </div>
        <div class="container" id="right">
            <form class="my-5" id="loginform" action="" method="POST">
                <div class="mb-3" id="wlcmmsg">
                    <h3>Hello,Again</h3>
                    <p>We are happy to have you back</p>

                </div>
                <div class="mb-3">
                    <label for="InputEmail" class="form-label">Email address</label>
                    <input type="email" value="<?php if(isset($_COOKIE['emailcookie'])){ echo $_COOKIE['emailcookie']; } ?>" class="form-control" name="email" id="InputEmail" aria-describedby="emailHelp" required>
                </div>
                <div class="mb-3">
                    <label for="InputPassword" class="form-label">Password</label>
                    <input type="password"  value="<?php if(isset($_COOKIE['passwordcookie'])){ echo $_COOKIE['passwordcookie']; } ?>"  name="password" class="form-control" id="InputPassword" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="rememberme" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    <a href="ForgotPassword.php" id="forgotpass" name="forgotpass" class="alert-link" style="color: slateblue;">Forgot password?</a>

                </div>

                <?php
                if (isset($_GET['token'])) {
                    echo '<input type="hidden" name="verify_token" value="' . htmlspecialchars($_GET['token']) . '">';
                }
                ?>

                <div class="d-flex justify-content-center">
                    <button type="submit" id="loginbtn" name="login" style="background-color:slateblue" class="btn">Login</button>
                </div>
                <br>
                <div class="mb-4">
                    <label for="signup" class="form-label">Don't have account?</label>
                    <a href="Usersignuppage.php" id="signup" name="signup" class="alert-link" style="color: slateblue;">Sign up</a>
                </div>
            </form>
        </div>

    </div>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>