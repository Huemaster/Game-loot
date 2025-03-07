<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"> 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: rgba(0, 0, 0, 0.7); 
            border-bottom: 3px solid #3498db; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            font-family: 'Press Start 2P', cursive;
            color: white;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: #f1c40f;
            font-size: 1.1rem;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        nav a:hover {
            color: #3498db;
        }

        .auth-btn {
            font-size: 1.1rem;
            padding: 8px 16px;
            background-color: #2c3e50;
            color: #ecf0f1;
            border-radius: 5px;
            border: 2px solid #3498db;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .auth-btn:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="explore_deals.php">Deals</a>
            <a href="community.php">Community</a>
            <a href="about.php">About</a>
        </nav>
        <div>
            <?php if (isset($_SESSION['user']) && $_SESSION['user'] !== 'guest'): ?>
                <!-- If session user is set and not a guest, display the username and logout button -->
                <a href="welcome.php"><button class="auth-btn user-btn"><?= htmlspecialchars($_SESSION['user']); ?></button></a>
                <a href="logout.php"><button class="auth-btn">Logout</button></a>
            <?php else: ?>
                <!-- If session is guest or not set, show login button -->
                <a href="login.php"><button class="auth-btn">Login</button></a>
            <?php endif; ?>
        </div>
    </header>   
</body>
</html>
