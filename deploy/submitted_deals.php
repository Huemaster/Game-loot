<?php
session_start();
include('database_connect.php');
include("header.php");
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to view your deals.'); window.location.href = 'login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's submitted deals
$sql = "SELECT d.deal_id, g.game_title AS game_title, d.platform, d.category, d.original_price, d.sale_price, 
        d.expiry_date, d.deal_url
        FROM Deals d
        JOIN Games g ON d.game_id = g.game_id
        WHERE d.user_id = ?";
$stmt = $dbc->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Submitted Deals</title>
    <style>
        
        body {
            font-family: 'VT323', monospace;
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

        .deals-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
            width: 90%;
            max-width: 800px;
            margin-top: 70px;
            text-align: center;
        }

        .deals-container h1 {
            font-size: 2rem;
            color: #f1c40f;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #34495e;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        td {
            background-color: #2c3e50;
        }

        a {
            color: #f1c40f;
            text-decoration: none;
        }

        a:hover {
            color: #f39c12;
        }

        .no-deals {
            font-size: 1.2rem;
            color: #e74c3c;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="deals-container">
        <h1>Your Submitted Deals</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>Platform</th>
                        <th>Original Price</th>
                        <th>Sale Price</th>
                        <th>Expires</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['game_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['platform']); ?></td>
                            <td><?php echo htmlspecialchars($row['original_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['sale_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($row['deal_url']); ?>" target="_blank">View Deal</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-deals">You did not submit any deals on games yet.</div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$stmt->close();
$dbc->close();
?>
<?php include("footer.php"); ?>