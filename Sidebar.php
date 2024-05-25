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
            margin-left: 280px; /* Width of the sidebar */
            padding: 20px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #6a5acd;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-auto sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 230px;">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-5">ChattingApp</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" data-page="Userhomepage.php" id="home-link">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white" data-page="DisplayAllFriendsList.php">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                        Friends
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white" data-page="DisplayAllUsersWhileAdding.php">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                        Add Friends
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white" data-page="Chat.php">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                        Chat
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white" data-page="DisplayReceivedFR.php">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                        Notifications
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?php echo $_SESSION['email']; ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="Profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="DeleteAccount.php">Delete Account</a></li>
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
