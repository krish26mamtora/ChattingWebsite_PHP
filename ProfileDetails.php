<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 10px;
            box-sizing: border-box;
        }
        #main {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #6a5acd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group input[readonly] {
            background-color: #e9ecef;
        }
        .form-group textarea {
            resize: vertical;
            height: 80px;
        }
        .profile-pic {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-pic img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #6a5acd;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .profile-pic input[type="file"] {
            display: none;
        }
        .profile-pic label {
            color: #6a5acd;
            cursor: pointer;
            padding: 5px 10px;
            border: 1px solid #6a5acd;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .profile-pic label:hover {
            background-color: #6a5acd;
            color: #ffffff;
        }
        .form-group.row {
            display: flex;
            justify-content: space-between;
        }
        .form-group.text-center {
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #6a5acd;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a4dcd;
        }
        #username {
            width: 300px;
        }
    </style>
</head>
<body>
    <div id="main">
        <form action="ProfileDetails.php" method="POST" enctype="multipart/form-data">
            <div class="form-group text-center">
                <h2>Please enter your details</h2>
            </div>
            <div class="profile-pic">
                <img id="profileImage" src="default-profile.png" alt="Profile Picture">
                <input type="file" id="profilePicInput" name="profilePic" accept="image/*" onchange="loadProfileImage(event)">
                <label for="profilePicInput">Upload Profile Picture</label>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email'];?>" readonly>
            </div>
            <div class="form-group row">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
               
            </div>
            <div class="form-group row">
                <div>
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country">
                </div>
                <div>
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
            </div>
            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about" name="about"></textarea>
            </div>
            <button type="submit">Save</button>
        </form>
    </div>
    <script>
        function loadProfileImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profileImage');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
