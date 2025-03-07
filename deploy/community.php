<?php 
    session_start();
    include('database_connect.php');
    include("header.php");  


    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

    $query = "
        SELECT d.discussion_id, d.discussion_title, g.game_title, COUNT(c.comment_id) AS number_of_comments
        FROM Discussions d
        LEFT JOIN Games g ON d.game_id = g.game_id
        LEFT JOIN Deals de ON de.game_id = g.game_id
        LEFT JOIN Comments c ON c.discussion_id = d.discussion_id
    ";

    // Modify the query to include a WHERE clause if a search term is provided
    if ($searchTerm) {
        $searchTerm = mysqli_real_escape_string($dbc, $searchTerm); // Sanitize the input
        $query .= " WHERE d.discussion_title LIKE '%$searchTerm%' OR g.game_title LIKE '%$searchTerm%'";
    }

    $query .= " GROUP BY d.discussion_id ORDER BY d.discussion_id DESC";

    $result = mysqli_query($dbc, $query);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Community Discussions - GameLoot</title>
        <style>
            body {
                font-family: 'VT323', monospace;
                background-image: url('background1.jpg');
                background-size: cover; 
                background-position: center;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                margin: 0;
                padding-top: 80px;
            }

            .h1 {
                font-size: 2rem;
                margin-bottom: 20px;
                color: #f1c40f;
                text-align: center;
                text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            }
            
            .search-container {
                margin-bottom: 30px;
                width: 80%;
                padding: 12px;
                font-size: 1rem;
                background-color: rgba(52, 152, 219, 0.7);
                border: 2px solid #3498db;
                border-radius: 20px;
                color: white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
                transition: all 0.3s ease;
            }

            .search-bar {
                width: 100%;
                padding: 15px 20px; 
                font-size: 1.2rem;
                background-color: rgba(52, 152, 219, 0.8);
                border: 2px solid #2980b9;
                border-radius: 25px;
                color: white;
                transition: all 0.3s ease;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            }

            .discussion-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
                width: 80%;
                padding-bottom: 20px;
            }

            .discussion-box {
                background-color: #34495e;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
                text-align: center;
                color: white;
                transition: transform 0.3s ease;
            }

            .discussion-box:hover {
                transform: translateY(-10px);
            }

            .title-box {
                background-color: #2c3e50; 
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 10px;
                font-weight: bold;
                color: #f39c12;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
            }

            .discussion-box h3 {
                font-size: 1.2rem;
                margin-bottom: 15px;
                color: white;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            }

            .discussion-box p {
                font-size: 0.9rem;
                margin-bottom: 15px;
                color: #ecf0f1;
            }

            .comments-box {
                background-color: #2c3e50; 
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 15px;
                font-weight: bold;
                color: #f39c12; 
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
                font-size: 1rem;
            }

            .buttons {
                text-align: center;
                
            }

            .buttons a {
                display: inline-block;
                text-decoration: none;
                padding: 12px 25px;
                background-color: #3498db;
                color: white;
                border-radius: 5px;
                font-size: 0.95rem;
                margin-bottom: 15px;
                transition: background-color 0.3s, transform 0.3s ease;
            }

            .buttons a:hover {
                background-color: #2980b9;
            }

            .create-button {
                background-color: #27ae60; 
                margin-bottom: 30px;
                color: white;
            }

            .create-button:hover {
                background-color: #2ecc71; 
            }
            .buttons button {
                display: inline-block;
                padding: 12px 25px;
                font-size: 0.95rem;
                color: white;
                background-color: #3498db;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            .buttons button:hover {
                background-color: #2980b9;
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
            <div class="h1">Community Discussions</div>

            <div class="buttons">
                <a href="create_discussion.php" class="create-button">Create New Discussion</a>
            </div>

            <div class="search-container">
            <form method="GET" action="">
                <input 
                    type="text" 
                    class="search-bar" 
                    name="search" 
                    placeholder="Search for discussions..." 
                    value="<?php echo htmlspecialchars($searchTerm); ?>"
                >
                <input type="submit" style="display:none;">
            </form>
        </div>

            <div class="discussion-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="discussion-box">

                        <div class="title-box">
                            <strong><?php echo htmlspecialchars($row['game_title']); ?></strong>
                        </div>
                        <div class="title-box">
                            <strong><?php echo htmlspecialchars($row['discussion_title']); ?></strong>
                        </div>

        
                        <div class="comments-box">
                            <?php 
                            if ($row['number_of_comments'] == 0) {
                                echo "No comments posted yet";
                            } else {
                                echo $row['number_of_comments'] . " comments";
                            }
                            ?>
                        </div>
                        
                        <div class="buttons">
                            <form method="GET" action="view_discussion.php">
                                <input type="hidden" name="discussion_id" value="<?php echo htmlspecialchars($row['discussion_id']); ?>">
                                <button type="submit">View Discussion</button>
                            </form>
                        </div>

                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </body>
    </html>
    <?php include("footer.php"); ?> 
