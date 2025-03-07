<?php
session_start();
include("header.php");
include('database_connect.php'); 
 
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $new_email = $_POST['new_email'];

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {

        $query = "SELECT user_id FROM users WHERE email = ?";
        if ($stmt = $dbc->prepare($query)) {
            $stmt->bind_param("s", $new_email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "Email is already in use.";
            } else {
                $update_query = "UPDATE users SET email = ? WHERE user_id = ?";
                if ($update_stmt = $dbc->prepare($update_query)) {
                    $update_stmt->bind_param("si", $new_email, $user_id);
                    if ($update_stmt->execute()) {
                        $success_message = "Email updated successfully.";
                    } else {
                        $error_message = "Error updating email. Please try again.";
                    }
                } else {
                    $error_message = "Error preparing update statement.";
                }
            }
        } else {
            $error_message = "Error checking email availability.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email</title>
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

        input[type="email"] {
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

        input[type="email"]:focus {
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

        .success {
            color: green;
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Change Email</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="success"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <form action="change_email.php" method="POST">
            <input type="email" name="new_email" placeholder="New Email Address" required>
            <input type="submit" value="Update Email">
        </form>
    </div>
</body>
</html>
<?php include("footer.php"); ?>
