<?php
session_start();
include('database_connect.php');
include("header.php");

// Check if discussion_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['discussion_id'])) {
    $discussion_id = intval($_POST['discussion_id']);
} elseif (isset($_GET['discussion_id'])) {
    $discussion_id = intval($_GET['discussion_id']);
} else {
    header("Location: community.php");
    exit();
}

// Determine if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check membership status only if user is logged in
$is_member = false;
if ($user_id !== null) {
    $check_query = "SELECT * FROM user_discussions WHERE user_id = ? AND discussion_id = ?";
    $stmt = mysqli_prepare($dbc, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $discussion_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $is_member = mysqli_num_rows($result) > 0;
}

// Handle join or leave discussion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_membership'])) {
    // User must be logged in to join or leave
    if ($user_id === null) {
        $_SESSION['need_login'] = "You need to be logged in to join or leave a discussion.";
        header("Location: login.php");
        exit();
    }

    if ($is_member) {
        // Leave discussion: delete entry
        $delete_query = "DELETE FROM user_discussions WHERE user_id = ? AND discussion_id = ?";
        $stmt = mysqli_prepare($dbc, $delete_query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $discussion_id);
        mysqli_stmt_execute($stmt);
        $is_member = false;
    } else {
        // Join discussion: insert entry
        $insert_query = "INSERT INTO user_discussions (user_id, discussion_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($dbc, $insert_query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $discussion_id);
        mysqli_stmt_execute($stmt);
        $is_member = true;
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    // User must be logged in to comment
    if ($user_id === null) {
        $_SESSION['need_login'] = "You need to be logged in to comment.";
        header("Location: login.php");
        exit();
    }

    $comment_text = trim($_POST['comment_text']);
    if (!empty($comment_text)) {
        // Check if the comment already exists
        $check_query = "SELECT * FROM Comments WHERE discussion_id = ? AND user_id = ? AND comment_text = ?";
        $stmt = mysqli_prepare($dbc, $check_query);
        mysqli_stmt_bind_param($stmt, 'iis', $discussion_id, $user_id, $comment_text);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // Insert comment since it's not a duplicate
            $insert_comment_query = "INSERT INTO Comments (discussion_id, user_id, comment_text, timestamp) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($dbc, $insert_comment_query);
            mysqli_stmt_bind_param($stmt, 'iis', $discussion_id, $user_id, $comment_text);
            mysqli_stmt_execute($stmt);
        }
    }
}

// Fetch discussion details
$query = "
    SELECT d.discussion_title, g.game_title 
    FROM Discussions d
    LEFT JOIN Games g ON d.game_id = g.game_id
    WHERE d.discussion_id = ?
";
$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, 'i', $discussion_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $discussion_title = htmlspecialchars($row['discussion_title']);
    $game_title = htmlspecialchars($row['game_title']);
} else {
    die("Discussion not found.");
}

// Fetch comments
$comments_query = "
    SELECT u.username, c.comment_text, c.timestamp 
    FROM Comments c
    JOIN Users u ON c.user_id = u.user_id
    WHERE c.discussion_id = ?
    ORDER BY c.timestamp ASC
";

$stmt = mysqli_prepare($dbc, $comments_query);
mysqli_stmt_bind_param($stmt, 'i', $discussion_id);
mysqli_stmt_execute($stmt);
$comments_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Discussion</title>
    <style>
        body {
            font-family: 'VT323', monospace;
            background-image: url('background1.jpg'); 
            background-size: cover; 
            background-position: center;
            padding: 20px;
            color: white;
        }
        .discussion-title {
            font-size: 2rem;
            text-align: center;
            color: #f1c40f;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            margin-top: 70px;
        }
        .game-title {
            font-size: 1.5rem;
            text-align: center;
            color: #3498db;
            margin-top: 20px;
        }
        .comments-section {
            margin: 20px auto;
            padding: 15px;
            max-width: 800px;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
        }

        .comment {
            padding: 10px;
            border-bottom: 1px solid #444;
            margin-bottom: 10px;
            color: #fff;
            text-shadow: 1px 1px 3px #555;
        }
        .comment-user {
            color: #f39c12;
        }
        .comment-text {
            margin-top: 5px;
        }
        .join-discussion button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            color: white;
            background-color: #27ae60;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .join-discussion button:hover {
            background-color: #2ecc71;
            transform: scale(1.1);
        }

        .join-discussion {
            text-align: center;
            margin-top: 20px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            resize: none;
            outline: none;
            text-shadow: 1px 1px 3px #555;
            margin-top: 20px;
        }

        .comment-form button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            color: white;
            background-color: #27ae60;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
        }

        .comment-form button:hover {
            background-color: #2ecc71;
        }

    </style>
</head>
<body>
    <div class="discussion-title"><?php echo $discussion_title; ?></div>
    <div class="game-title">Game: <?php echo $game_title; ?></div>

    <div class="join-discussion">
        <form method="POST">
            <input type="hidden" name="discussion_id" value="<?php echo $discussion_id; ?>">
            <button type="submit" name="toggle_membership">
                <?php echo $is_member ? 'Leave Discussion' : 'Join Discussion'; ?>
            </button>
        </form>
    </div>

    <?php if ($user_id !== null && $is_member): ?>
    <div class="comment-form">
        <h3>Leave a Comment</h3>
        <form method="POST">
            <input type="hidden" name="discussion_id" value="<?php echo $discussion_id; ?>">
            <textarea name="comment_text" rows="4" cols="50" placeholder="Write your comment here..." required></textarea><br>
            <button type="submit">Submit Comment</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="comments-section">
        <h3>Comments</h3>
        <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
            <div class="comment">
                <div class="comment-user">Username: <?php echo htmlspecialchars($comment['username']); ?></div>
                <div class="comment-text"><?php echo htmlspecialchars($comment['comment_text']); ?></div>
                <div class="comment-date"><?php echo htmlspecialchars($comment['timestamp']); ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php include("footer.php"); ?>
