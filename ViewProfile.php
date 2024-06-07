<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="ProfileStyle.css">

</head>

<body>
    <div id="main">
        <form action="Sidebar.php" method="POST" enctype="multipart/form-data">
            <div class="form-group text-center">
                <h2>User's Profile</h2>
            </div>
            <div class="profile-pic">
                <?php

                session_start();
                if (file_exists('partials/db_connect.php')) {
                    include 'partials/db_connect.php';
                } else {
                    echo "connection file not found.";
                }

                if (isset($_POST['ViewProfile'])) {
                    $UserUID = $_POST['UID'];
                    $UserEmail = $_POST['senduseremail'];
                    $FetchUserdata = "SELECT * FROM user_profile WHERE UID = '$UserUID'";
                    $FetchUserdata_run = mysqli_query($link, $FetchUserdata);
                    if ($FetchUserdata_run && mysqli_num_rows($FetchUserdata_run) > 0) {
                        while ($row = mysqli_fetch_assoc($FetchUserdata_run)) {
                            $FindCon = "SELECT * FROM user_connections WHERE UID = '$UserUID'";
                            $FindCon_run = mysqli_query($link, $FindCon);
                            $count = 0;
                            if ($FindCon_run && mysqli_num_rows($FindCon_run) > 0) {
                                while ($friend = mysqli_fetch_assoc($FindCon_run)) {

                                    $pattern = "/\b\d+\b/";
                                    preg_match_all($pattern, $friend['Friends'], $matches);
                                    $totalNumbers = count($matches[0]);
                                }
                            }
                            $profilePicPath = $row['profile_pic'];
                            echo '<img id="profileImage" src="' . $profilePicPath . '" alt="Profile Picture">';

                ?>
            </div>


            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" readonly value="<?php echo $UserEmail; ?>">
            </div>
            <div class="form-group row">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo  $row['username']; ?>" readonly>
                </div>
                <div>
                    <label for="username">Connections</label>
                    <input type="text" id="Connections" name="Connections" value="<?php echo $totalNumbers; ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" readonly>
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about" name="about" readonly><?php echo $row['About']; ?></textarea>
            </div>

            <button type="submit">Back</button>
        </form>
    </div>
</body>

</html>

<?php

                        }
                    }
                }
?>