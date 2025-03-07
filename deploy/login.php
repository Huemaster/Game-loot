<?php
include("header.php"); 
include('database_connect.php'); 
session_start();
$error = '';

$loginMessage = '';
if (isset($_SESSION['need_login'])) {
    $loginMessage = $_SESSION['need_login'];
    unset($_SESSION['need_login']); // Clear message after displaying
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($dbErrorMessage)) {
    $username_or_email = mysqli_real_escape_string($dbc, $_POST['username_or_email']);
    $password = mysqli_real_escape_string($dbc, $_POST['password']);

    // Query the database for the user
    $query = "SELECT * FROM Users WHERE username = '$username_or_email' OR email = '$username_or_email'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['user_id'] = $row['user_id']; 
            header("Location: welcome.php"); 
            exit();
        } else {
            $error = "Invalid username, email, or password.";
        }
    } else {
        $error = "Invalid username, email, or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameLoot</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
            background-image: url('https://img.freepik.com/free-vector/abstract-futuristic-background-concept_23-2148409810.jpg?t=st=1732134560~exp=1732138160~hmac=a4dd97aa85455bd104184eb49b45b2aa064b78370d0058279accd5e9c037c689&w=900'); 
            background-size: cover; 
            background-position: center;
            background-color: #2c3e50;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
            text-align: center;
            width: 300px;
        }

        .login-container h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #f1c40f;
        }

        .login-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-container input[type="text"], .login-container input[type="password"] {
            background-color: #34495e;
            color: white;
        }

        .login-container input[type="submit"] {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        .login-container input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            font-size: 0.8rem;
        }
        
        #messageContainer {
            background-color: #f39c12;
            color: black;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            display: <?php echo $loginMessage ? 'block' : 'none'; ?>;
        }

        .create-account-btn {
            background-color: #27ae60;
            color: white;
            font-size: 1rem;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 90%;
        }

        .create-account-btn:hover {
            background-color: #2ecc71;
        }

        .database-error {
            background-color: #e74c3c;
            color: white;
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            width: 300px;
        }
    </style>
</head>
<body>
    <?php if (!empty($dbErrorMessage)): ?>
        <div class="database-error"><?php echo $dbErrorMessage; ?></div>
    <?php else: ?>
        <div class="login-container">
            <div id="messageContainer"><?php echo $loginMessage; ?></div>
            <h1>Login</h1>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="text" name="username_or_email" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
            <button class="create-account-btn" onclick="window.location.href='create_account.php'">I don't have an account</button>
        </div>
    <?php endif; ?>
</body>
</html>
<?php include("footer.php"); ?> 
