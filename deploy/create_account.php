<?php
include('database_connect.php');
include('header.php');

$username = $email = $confirm_email = $password = "";
$username_err = $email_err = $confirm_email_err = $password_err = $age_err = "";
$email_exists_err = $username_exists_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["confirm_email"]))) {
        $confirm_email_err = "Please confirm your email address.";
    } elseif ($_POST["email"] !== $_POST["confirm_email"]) {
        $confirm_email_err = "Email addresses do not match.";
    } else {
        $confirm_email = trim($_POST["confirm_email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (!isset($_POST["age_agreement"])) {
        $age_err = "You must be 13 years of age or older and agree to the terms.";
    }
    // Check if the email is already in use
    if (!empty($email)) {
        $email_check_sql = "SELECT user_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($dbc, $email_check_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $email_exists_err = "This email is already registered.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Check if the username is already in use
    if (!empty($username)) {
        $username_check_sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($dbc, $username_check_sql);
        mysqli_stmt_bind_param($stmt, "s", $username);

        if ($stmt && mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $username_exists_err = "This username is already taken.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    if (empty($username_err) && empty($email_err) && empty($confirm_email_err) && empty($password_err) && empty($age_err)&& empty($email_exists_err) && empty($username_exists_err)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    
        $stmt = mysqli_prepare($dbc, $sql);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hash);
    
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
            if (mysqli_stmt_execute($stmt)) {
                session_start();
                $_SESSION['need_login'] = "Account created successfully.";
                header("Location: login.php");
                exit();
            } else {
                echo "Something went wrong. Please try again.";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="form-container">
    <h1>Create Account</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
        <div class="error"><?php echo $username_exists_err; ?></div>
        <div class="error"><?php echo $username_err; ?></div>

        <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
        <div class="error"><?php echo $email_exists_err; ?></div>
        <div class="error"><?php echo $email_err; ?></div>

        <input type="email" name="confirm_email" placeholder="Confirm Email" value="<?php echo $confirm_email; ?>">
        <div class="error"><?php echo $confirm_email_err; ?></div>

        <input type="password" name="password" placeholder="Password">
        <div class="error"><?php echo $password_err; ?></div>

        <input type="checkbox" name="age_agreement" value="1"> I am 13 years of age or older and agree to the terms of the <a href="terms_conditions.php">Gameloot Agreement</a> and <a href="terms_conditions.php">Privacy Policy</a>.
        <div class="error"><?php echo $age_err; ?></div>

        <input type="submit" value="Create Account">
    </form>
</div>

<div class="benefits">
    <h2>Why Create an Account?</h2>
    <ul>
        <li>Submit and share the best game deals.</li>
        <li>Leave comments and join discussions.</li>
        <li>Create a wishlist and get notifications for sales.</li>
    </ul>
</div>

</body>
</html>
<?php include("footer.php"); ?> 