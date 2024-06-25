<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sidebar {
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
        }

        .content {
            margin-left: 280px;
            /* Width of the sidebar */
            padding: 20px;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #007bff ;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 230px;">
                <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <svg class="bi pe-none me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <span class="fs-5">ChattingApp</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-page="Userhomepage.php" id="home-link">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <!-- <use xlink:href="#home"></use> -->
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" data-page="DisplayAllFriendsList.php">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <!-- <use xlink:href="#speedometer2"></use> -->
                            </svg>
                            Friends
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" data-page="SendRequest.php">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <!-- <use xlink:href="#table"></use> -->
                            </svg>
                            Add Friends
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" data-page="iindex.php">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <!-- <use xlink:href="#grid"></use> -->
                            </svg>
                            Chat
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" data-page="DisplayReceivedFR.php">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <!-- <use xlink:href="#people-circle"></use> -->
                            </svg>
                            Notifications
                        </a>
                    </li>
                </ul>
                <hr>
                <?php
                $email= $_SESSION['email'];
                if (file_exists('partials/db_connect.php')) {
                    include 'partials/db_connect.php';
                } else {
                    die("Connection file not found.");
                }
                $UIDofCurrentLoginUser = "SELECT * FROM user_details WHERE email = '$email'";
                $result = mysqli_query($link, $UIDofCurrentLoginUser);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $CurrentLoginUID = $row['UID'];
                } else {
                    die("User not found.");
                }
                $profilepic = "SELECT * FROM user_profile WHERE UID = '$CurrentLoginUID'";
                $profilepic_run = mysqli_query($link, $profilepic);
                if ($profilepic_run && mysqli_num_rows($profilepic_run) > 0) {
                    $profie = mysqli_fetch_assoc($profilepic_run);
                    $username = $profie['username'];
                    $profile_pic = $profie['profile_pic'];
                }

                ?>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $profile_pic;?>" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong><?php echo $username; ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <!-- <li><a class="dropdown-item" href="ProfileDetails.php">Profile</a></li> -->
                        <li>
                        <a href="#" class="nav-link text-white" data-page="ProfileDetails.php">
                            <svg class="bi pe-none me-2" width="16" height="16">
                            </svg>
                            Profile
                        </a>
                    </li>
                        <li><a class="dropdown-item" href="logout.php">log out</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form id="delete-account-form" action="Sidebar.php" method="post">
                                <input type="hidden" name="Delete_Account" value="true">
                                <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer; ">Delete Account</button>
                            </form>

                        </li>
                        <?php

                        ?>
                    </ul>
                </div>
            </div>
            <div class="col content" id="main-content">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadPage(page) {
                $.ajax({
                    url: page,
                    method: 'GET',
                    success: function(data) {
                        $('#main-content').html(data);
                    },
                    error: function() {
                        $('#main-content').html('<p>Error loading page.</p>');
                    }
                });
            }

            loadPage('Userhomepage.php');
            $('#home-link').addClass('active');

            $('.nav-link').click(function(e) {
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                var page = $(this).data('page');
                loadPage(page);
            });
        });
    </script>
</body>

</html>

<?php
if (isset($_POST['Delete_Account'])) {

    if (file_exists('partials/db_connect.php')) {
        include 'partials/db_connect.php';
    } else {
        echo "Connection file not found.";
    }

    $email = $_SESSION['email'];
    $UIDofCurrentLoginUser = "SELECT UID FROM user_details WHERE email = '$email'";

    $result = mysqli_query($link, $UIDofCurrentLoginUser);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $CurrentLoginUID = $row['UID'];
    }

    $delfromdetails = "DELETE FROM `user_details` WHERE `UID` = '$CurrentLoginUID'";
    $delfromconnections = "DELETE FROM `user_connections` WHERE `UID` = '$CurrentLoginUID'";
    $delfromprofile = "DELETE FROM `user_profile` WHERE `UID` = '$CurrentLoginUID'";

    $result1 = mysqli_query($link, $delfromdetails);
    $result2 = mysqli_query($link, $delfromconnections);
    $result3 = mysqli_query($link, $delfromprofile);


    if ($result1 && $result2 && $result3) {
        echo "Account deleted successfully.";
        session_unset();
        session_destroy();
        header("Location: Userloginpage.php");
        exit();
    } else {
        echo "Error deleting account.";
    }
}
?>