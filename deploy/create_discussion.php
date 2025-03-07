<?php
session_start();
include('database_connect.php');
include('header.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['need_login'] = "You need to be logged in to create a discussion.";
    header("Location: login.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Fetch the list of games from the database
$games_query = "SELECT game_id, game_title FROM Games ORDER BY game_title ASC";
$games_result = mysqli_query($dbc, $games_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'] ?? null;
    $discussion_title = trim($_POST['discussion_title'] ?? '');

    // Validate inputs
    if (!$game_id || !$discussion_title) {
        $error_message = "Both game selection and discussion title are required.";
    } else {
        // Insert discussion into the database
        $insert_query = "INSERT INTO Discussions (game_id, user_id, discussion_title) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($dbc, $insert_query);
        mysqli_stmt_bind_param($stmt, 'iis', $game_id, $user_id, $discussion_title);

        if (mysqli_stmt_execute($stmt)) {
            // Retrieve the ID of the newly created discussion
            $discussion_id = mysqli_insert_id($dbc);
            $_SESSION['discussion_id'] = $discussion_id;

            // Redirect to view_discussion.php
            header("Location: view_discussion.php");
        
            exit();
        } else {
            $error_message = "Failed to create discussion. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Discussion</title>
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
            text-align: center;
        }

        select, input[type="text"], button {
            margin-top: 5px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #555;
            border-radius: 8px;
            background-color: #222;
            color: white;
            transition: all 0.3s ease-in-out;
        }

        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #00ff00;
            box-shadow: 0 0 8px #00ff00;
        }

        .container button {
            background: linear-gradient(45deg, #007BFF, #0056b3);
            color: white;
            border: none;
            cursor: pointer;
        }

        .container button:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Discussion</h1>
        <?php if (isset($error_message)): ?>
            <div class="error"> <?php echo $error_message; ?> </div>
        <?php endif; ?>
        <form method="POST">
            <label for="game_id">Select Game:</label>
            <select name="game_id" id="game_id" required>
                <option value="">-- Select a Game --</option>
                <?php while ($game = mysqli_fetch_assoc($games_result)): ?>
                    <option value="<?php echo $game['game_id']; ?>">
                        <?php echo htmlspecialchars($game['game_title']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="discussion_title">Discussion Title:</label>
            <input type="text" name="discussion_title" id="discussion_title" maxlength="255" required>

            <button type="submit">Create Discussion</button>
        </form>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
