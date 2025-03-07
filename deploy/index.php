<?php session_start(); ?>
<?php include("header.php"); ?>
<?php include('database_connect.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameLoot - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            flex-direction: column;
            margin: 0;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            width: 90%;
            max-width: 500px;
        }

        body h1 {
            font-size: 2.5rem;
            color: #ffcc00;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .container p {
            color: #f1c40f;
            padding-bottom: 30px;
            font-size: 1.2rem;
            font-weight: 300;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            width: 100%; 
            padding: 12px 20px;
            font-size: 1.2rem;
            text-transform: uppercase;
            font-weight: bold;
            background-color: #2c3e50;
            color: #ecf0f1;
            border: 3px solid #3498db;
            border-radius: 10px;
            cursor: pointer;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: all 0.3s;
        }

        .create-account {
            background-color: #e74c3c;
            border-color: #c0392b;
        }

        .log-in {
            background-color: #27ae60;
            border-color: #2ecc71;
        }

        .guest {
            background-color: #f39c12;
            border-color: #e67e22;
        }

        .create-account:hover {
            background-color: #c0392b;
        }

        .log-in:hover {
            background-color: #2ecc71;
        }

        .guest:hover {
            background-color: #e67e22;
        }

        
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"> 
</head>
<body>
<div class="container">
        <h1>GameLoot</h1>
        <p>Your Community for Finding the Best Game Deals!</p>

        <main>
            <div class="buttons">
                <a href="create_account.php">
                    <button class="btn create-account">Create Account</button>
                </a>
                <a href="login.php">
                    <button class="btn log-in">Log In</button>
                </a>
                <a href="explore_deals.php">
                    <button class="btn guest">Start Saving Now</button>
                </a>
            </div>
        </main>
    </div>
    
</body>
</html>
<?php include("footer.php"); ?>