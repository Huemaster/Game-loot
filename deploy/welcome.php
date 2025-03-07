<?php
session_start();
include("header.php"); 

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['user'];
$user_id= $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - GameLoot</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'VT323', monospace;
            background-color: #2c3e50;
            background-image: url('https://img.freepik.com/free-vector/abstract-futuristic-background-concept_23-2148409810.jpg?t=st=1732134560~exp=1732138160~hmac=a4dd97aa85455bd104184eb49b45b2aa064b78370d0058279accd5e9c037c689&w=900'); 
            background-size: cover; 
            background-position: center;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .welcome-container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
            text-align: center;
            width: 400px;
        }

        .welcome-container h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #f1c40f;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #7f8c8d;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: white;
            text-transform: uppercase;
            overflow: hidden;
        }

        .options {
            text-align: left;
            margin-top: 20px;
        }

        .options a {
            display: block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .options a:hover {
            background-color: #2980b9;
        }

        .logout-btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="profile-pic">
            <?php 
            // Display first letter of username as a placeholder for the profile picture
            echo strtoupper($username[0]); 
            ?>
        </div>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <div class="options">
            <a href="view_wishlist.php">View Wishlist</a>
            <a href="submitted_deals.php">View Submitted Deals</a>
            <a href="joined_discussions.php">Joined Discussions</a>
            <a href="view_notifications.php">View Notifications</a>
            <a href="change_password.php">Change Password</a>
            <a href="change_email.php">Change Email</a>
        </div>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</body>
</html>
<?php include("footer.php"); ?> 
