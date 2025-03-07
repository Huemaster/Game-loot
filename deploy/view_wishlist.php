<?php
session_start();
include("header.php");
include('database_connect.php');

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user'];
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit();
}

// Fetch the user's wishlist from the database
$query = "SELECT w.wishlist_id, g.game_title, g.game_id
          FROM Wishlist w
          JOIN Games g ON w.game_id = g.game_id
          WHERE w.user_id = ?";
$stmt = $dbc->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
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

        .container {
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
        }

        .wishlist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #222;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .wishlist-item span {
            font-size: 16px;
            color: #bbb;
        }

        .wishlist-item a {
            text-decoration: none;
            background-color: #00ff00;
            color: black;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .wishlist-item a:hover {
            background-color: #0056b3;
            color: white;
        }

        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }

        .buttons-container a {
            margin: 10px;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s;
            width: calc(70% - 20px); 
            text-align: center;
        }

        .buttons-container a:hover {
            background-color: #218838;
        }

        .alert {
            color: #d9534f;
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }

        .alert a {
            color: #00ff00;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Your Wishlist</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="wishlist-item">
                    <span><?php echo htmlspecialchars($row['game_title']); ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert">
                No games found in your wishlist.
            </div>
        <?php endif; ?>

        <div class="buttons-container">
            <a href="add_games_wishlist.php">Add Game to Wishlist</a>
            <a href="welcome.php">Return to Home</a>
        </div>
    </div>

</body>
</html>
<?php include("footer.php"); ?>