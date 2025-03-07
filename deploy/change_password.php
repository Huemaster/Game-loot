<?php
session_start();
include("header.php");
include('database_connect.php'); 
 
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New passwords do not match.";
    } else {
        $query = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $dbc->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($current_password, $hashed_password)) {
                if (password_verify($new_password, $hashed_password)) {
                    $error_message = "New password cannot be the same as the current password.";
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
                    $update_stmt = $dbc->prepare($update_query);
                    $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                    if ($update_stmt->execute()) {
                        $success_message = "Password updated successfully.";
                    } else {
                        $error_message = "Failed to update password. Please try again.";
                    }
                }
            } else {
                $error_message = "Current password is incorrect.";
            }
        } else {
            $error_message = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - GameLoot</title>
    <style>
        body {
            font-family: 'Press Start 2P', cursive; 
            background-image: url('https://img.freepik.com/free-vector/abstract-futuristic-background-concept_23-2148409810.jpg?t=st=1732134560~exp=1732138160~hmac=a4dd97aa85455bd104184eb49b45b2aa064b78370d0058279accd5e9c037c689&w=900'); 
            background-size: cover; 
            background-position: center; 
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 50px;
            border-radius: 15px;
            width: 90%;
            max-width: 700px;
            margin: 100px auto;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
            border: 2px solid #00ff00;
        }

        h1 {
            font-size: 24px;
            color: #00ff00;
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="checkbox"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #555;
            border-radius: 8px;
            background-color: #222;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease-in-out;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #00ff00;
            box-shadow: 0 0 8px #00ff00;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: linear-gradient(45deg, #007BFF, #0056b3);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h1>Change Password</h1>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">

            <input type="password" id="current_password" name="current_password" placeholder="Current Password" required>

            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>

            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>

            <input type="submit" value="Update Password">
        </form>
    </div>
</body>
</html>
<?php include("footer.php"); ?>