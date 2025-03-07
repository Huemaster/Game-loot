<?php 
session_start();  
include("header.php"); 
include('database_connect.php'); 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store the message in the session
    $_SESSION['need_login'] = "You need to be logged in to submit a deal.";

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Function to escape user inputs
function escapeInput($data) {
    global $dbc;
    return mysqli_real_escape_string($dbc, trim($data));
}

$message = ''; // Initialize a variable to store the message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $platform = escapeInput($_POST['platform']);
    $category = escapeInput($_POST['category']);
    $original_price = escapeInput($_POST['original_price']);
    $sale_price = escapeInput($_POST['sale_price']);
    $expiry_date = escapeInput($_POST['expiry_date']);
    $deal_url = escapeInput($_POST['deal_url']);
    $game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;
    $new_game = isset($_POST['new_game']) ? escapeInput($_POST['new_game']) : null;

    // Check if the expiry date is in the future
    $current_date = date('Y-m-d');
    if ($expiry_date <= $current_date) {
        $message = "<p style='color: orange; text-align: center;'>The deal expiration date must be in the future.</p>";
    } elseif ($sale_price >= $original_price) { 
        $message = "<p style='color: orange; text-align: center;'>The sale price must be less than the original price.</p>";
    }else {
        // Check if game is selected or manually entered
        if (!$game_id && !$new_game) {
            $message = "<p style='color: red; text-align: center;'>Please select a game from the list or manually enter a new game.</p>";
        } else {
            // Insert the new game into the Games table if manually entered
            if ($new_game) {
                $game_query = "INSERT INTO Games (game_title, platform, category) VALUES ('$new_game', '$platform', '$category')";
                if (mysqli_query($dbc, $game_query)) {
                    $game_id = mysqli_insert_id($dbc);
                } else {
                    $message = "<p style='color: red; text-align: center;'>Error adding new game: " . mysqli_error($dbc) . "</p>";
                }
            }

            // Insert deal into Deals table
            $query = "INSERT INTO Deals (game_id, user_id, platform, category, original_price, sale_price, expiry_date, deal_url)
                      VALUES ('$game_id', '$user_id', '$platform', '$category', '$original_price', '$sale_price', '$expiry_date', '$deal_url')";
            if (mysqli_query($dbc, $query)) {
                $message = "<p style='color: green; text-align: center;'>Thank you for submitting a deal!</p>";
            } else {
                $message = "<p style='color: red; text-align: center;'>Error submitting deal: " . mysqli_error($dbc) . "</p>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Deal - GameLoot</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"> 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Press Start 2P', cursive;
            background-image: url('background1.jpg'); 
            background-size: cover; 
            background-position: center;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 20px 30px; 
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            width: 80%; 
            max-width: 700px; 
            margin: 50px auto; 
            margin-top: 100px;
        }

        .container h1 {
            font-size: 2rem; 
            color: #ffcc00;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-size: 1rem;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 2px solid #3498db;
            font-size: 1rem;
            text-transform: uppercase;
        }

        form button {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2c3e50;
        }

        #newGameInput {
            display: none;
            margin-top: 10px;
        }

        /* Message container style */
        #messageContainer {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: #2c3e50;
            color: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Submit a Deal</h1>
        
        <!-- Hidden message container -->
        <div id="messageContainer">
            <?php echo $message; ?>
        </div>

        <form method="POST" action="">
            <label for="game_id">Select Game</label>
            <select id="game_id" name="game_id">
                <option value="">-- Select a Game --</option>
                <?php
                // Fetch games from the database
                $game_query = "SELECT game_id, game_title FROM Games ORDER BY game_title";
                $games_result = mysqli_query($dbc, $game_query);
                while ($game = mysqli_fetch_assoc($games_result)) {
                    echo "<option value='" . htmlspecialchars($game['game_id']) . "'>" . htmlspecialchars($game['game_title']) . "</option>";
                }
                ?>
            </select>
            <button type="button" id="newGameBtn" onclick="toggleNewGameInput()">I can't see the game</button>
            <div id="newGameInput">
                <label for="new_game">Enter Game Name</label>
                <input type="text" id="new_game" name="new_game" placeholder="Enter the game title">
            </div>

            <label for="platform">Platform</label>
            <input type="text" id="platform" name="platform" placeholder="e.g., Steam, GOG" required>

            <label for="category">Category</label>
            <input type="text" id="category" name="category" placeholder="e.g., RPG, Action" required>

            <label for="original_price">Original Price ($)</label>
            <input type="number" id="original_price" name="original_price" step="0.01" placeholder="Original price" required>

            <label for="sale_price">Sale Price ($)</label>
            <input type="number" id="sale_price" name="sale_price" step="0.01" placeholder="Sale price" required>

            <label for="expiry_date">Deal Expiry Date</label>
            <input type="date" id="expiry_date" name="expiry_date" required>

            <label for="deal_url">Deal URL</label>
            <input type="url" id="deal_url" name="deal_url" placeholder="e.g., https://..." required>

            <button type="submit">Submit Deal</button>
        </form>
    </div>

    <script>
        // Function to toggle the visibility of the new game input
        function toggleNewGameInput() {
            const newGameInput = document.getElementById('newGameInput');
            newGameInput.style.display = newGameInput.style.display === 'none' ? 'block' : 'none';
        }

        // Show the message container if there's a message
        window.onload = function() {
            const messageContainer = document.getElementById('messageContainer');
            if (messageContainer.innerHTML.trim() !== "") {
                messageContainer.style.display = 'block';
            }
        };
    </script>

</body>
</html>

<?php include("footer.php"); ?>
