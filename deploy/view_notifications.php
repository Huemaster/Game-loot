<?php
session_start();
include('database_connect.php');
include('header.php');

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's wishlist games
$query = "SELECT w.game_id, g.game_title 
          FROM Wishlist w
          JOIN Games g ON w.game_id = g.game_id
          WHERE w.user_id = ?";
$stmt = $dbc->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlist_games = [];
while ($row = $result->fetch_assoc()) {
    $wishlist_games[] = $row['game_id'];
}

// Prepare the result for deals
$deals_result = null;

if (!empty($wishlist_games)) {
    // Build a query with placeholders for each wishlist game ID
    $placeholders = implode(",", array_fill(0, count($wishlist_games), '?'));
    $deals_query = "SELECT d.deal_id, g.game_title, d.platform, d.original_price, d.sale_price, d.expiry_date, d.deal_url
                    FROM Deals d
                    JOIN Games g ON d.game_id = g.game_id
                    WHERE d.game_id IN ($placeholders)
                    AND d.expiry_date >= CURDATE()";

    $stmt = $dbc->prepare($deals_query);
    if ($stmt) {
        $types = str_repeat('i', count($wishlist_games));
        $stmt->bind_param($types, ...$wishlist_games);
        $stmt->execute();
        $deals_result = $stmt->get_result();
    } else {
        // If prepare fails, no deals are displayed, but user is not blocked
        $deals_result = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
            background-image: url('https://img.freepik.com/free-vector/abstract-futuristic-background-concept_23-2148409810.jpg');
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

        .deal {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #00ff00;
            color: white;
        }

        .deal h2 {
            color: #00ff00;
            margin: 10px 0;
        }

        .deal p {
            font-size: 14px;
            margin: 5px 0;
        }

        .deal .deal-link {
            color: #007BFF;
            text-decoration: none;
        }

        .deal .deal-link:hover {
            color: #00ff00;
        }

        .no-notifications {
            color: red;
            font-size: 16px;
            margin-top: 20px;
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
    </style>
</head>
<body>

<div class="container">
    <h1>Your Notifications</h1>

    <?php if (empty($wishlist_games) || $deals_result === null || $deals_result->num_rows === 0): ?>
        <p class="no-notifications">You don't have any new notifications for your wishlist.</p>
    <?php else: ?>
        <?php while ($deal = $deals_result->fetch_assoc()): ?>
            <div class="deal">
                <h2>Deal on <?php echo htmlspecialchars($deal['game_title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><strong>Platform:</strong> <?php echo htmlspecialchars($deal['platform'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Original Price:</strong> $ <?php echo htmlspecialchars($deal['original_price'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Sale Price:</strong> $ <?php echo htmlspecialchars($deal['sale_price'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Expires on:</strong> <?php echo htmlspecialchars($deal['expiry_date'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><a href="<?php echo htmlspecialchars($deal['deal_url'], ENT_QUOTES, 'UTF-8'); ?>" class="deal-link" target="_blank">Check the Deal</a></p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <div class="buttons-container">
        <a href="welcome.php">Return Home</a>
    </div>
</div>

</body>
</html>

<?php include("footer.php"); ?>
