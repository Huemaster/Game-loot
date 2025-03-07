<?php
session_start();
include("header.php");
include('database_connect.php');

// Make sure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission for adding a game to the wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];

    // Add game to wishlist
    $query = "INSERT INTO Wishlist (user_id, game_id) VALUES (?, ?)";
    $stmt = $dbc->prepare($query);
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();

    // Redirect back to wishlist after adding
    header("Location: view_wishlist.php");
    exit();
}

// Fetch all games from the 'game' table
$query = "SELECT game_id, game_title FROM games";
$result = $dbc->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game to Wishlist</title>
    <link rel="stylesheet" href="styles.css">
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

        select {
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

        select:focus {
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
        <h1>Add Game to Wishlist</h1>

        <!-- Form for adding a game to the wishlist -->
        <form action="add_games_wishlist.php" method="POST">
            <label for="game_id">Select Game:</label>
            <select id="game_id" name="game_id" required>
                <option value="" disabled selected>Select a game</option>
                <?php
                // Loop through the result set and create options for each game
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['game_id']}'>{$row['game_title']}</option>";
                }
                ?>
            </select>

            <input type="submit" value="Add Game">
        </form>

    </div>

</body>
</html>
<?php include("footer.php"); ?>
