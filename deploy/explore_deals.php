<?php session_start(); 
include("header.php"); 
include('database_connect.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Deals - GameLoot</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet"> 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'VT323', monospace;
            background-image: url('background1.jpg'); 
            background-size: cover; 
            background-position: center;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            padding-top: 120px; 
            padding-bottom: 60px; 
            flex-grow: 1; 
            margin-bottom: 60px; 
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 20px 30px; 
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            width: 80%; 
            max-width: 700px; 
            margin: 0 auto; 
        }

        .container h1 {
            font-size: 2rem; 
            color: #ffcc00;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .deal-card {
            background-color: rgba(0, 0, 0, 0.8);
            margin: 10px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.7);
        }

        .deal-card h3 {
            font-size: 1.4rem; 
            color: #e74c3c;
            margin-bottom: 8px;
        }

        .deal-card p {
            font-size: 0.9rem; 
            margin-bottom: 8px;
        }

        .deal-card .price {
            font-size: 1.2rem;
            color: #f39c12;
            margin-bottom: 8px;
        }

        .deal-card .deal-link {
            font-size: 1rem; 
            color: #2ecc71;
            text-decoration: none;
            margin-top: 8px;
            display: inline-block;
            font-weight: bold;
        }

        .deal-card .deal-link:hover {
            text-decoration: underline;
        }

        .buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 18px;
            font-size: 1rem; 
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

        .btn:hover {
            background-color: #3498db;
        }
        
        .database-error {
            background-color: #e74c3c;
            color: white;
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            width: 300px;
        }

    </style>
</head>
<body>
<?php if (!empty($dbErrorMessage)): ?>
        <div class="database-error"><?php echo $dbErrorMessage; ?></div>
    <?php else: ?>
        <div class="content">
            <div class="container">
                <div class="buttons">
                    <a href="submit_deal.php">
                        <button class="btn">Submit a New Deal</button>
                    </a>
                </div>

                <h1>Explore Deals</h1>

                <?php
                // Fetch active deals
                $query = "SELECT d.deal_id, g.game_title, g.category, d.platform, d.original_price, d.sale_price, d.deal_url, d.expiry_date 
                        FROM Deals d
                        JOIN Games g ON d.game_id = g.game_id
                        WHERE d.expiry_date > NOW() 
                        ORDER BY d.submission_date DESC";
                $result = mysqli_query($dbc, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($deal = mysqli_fetch_assoc($result)) {
                        echo "
                            <div class='deal-card'>
                                <h3>" . htmlspecialchars($deal['game_title']) . "</h3>
                                <p><strong>Category:</strong> " . htmlspecialchars($deal['category']) . "</p>
                                <p><strong>Platform:</strong> " . htmlspecialchars($deal['platform']) . "</p>
                                <p><strong>Original Price:</strong> $" . number_format($deal['original_price'], 2) . "</p>
                                <p><strong>Sale Price:</strong> $" . number_format($deal['sale_price'], 2) . "</p>
                                <p><strong>Deal Expiry:</strong> " . date('F j, Y', strtotime($deal['expiry_date'])) . "</p>
                                <a href='" . htmlspecialchars($deal['deal_url']) . "' class='deal-link' target='_blank'>View Deal</a>
                            </div>
                        ";
                    }
                } else {
                    echo "<p>No active deals found at the moment.</p>";
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
</body>
</html>

<?php include("footer.php"); ?>
