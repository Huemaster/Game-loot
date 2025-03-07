<?php
session_start();
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            font-family: 'VT323', monospace;
            background-color: #f5f5f5;
            background-image: url('https://img.freepik.com/free-vector/abstract-futuristic-background-concept_23-2148409810.jpg?t=st=1732134560~exp=1732138160~hmac=a4dd97aa85455bd104184eb49b45b2aa064b78370d0058279accd5e9c037c689&w=900');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            width: 90%;
            max-width: 11500px;
        }
        body h1 {
            font-size: 2.5rem;
            color: #ffcc00;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        body p {
            font-size: 18px;
            color: #F8F8FF;
            line-height: 2;
            margin: 0 10px;
            font-weight: 300;
            font-size: 1.2rem;
            
        }

        
    </style>

</head>
<body>
<div class="container">
    <h1>About Gameloot</h1>
    <p>This platform is a community-powered hub where gamers share and find the best deals on games. 
        Users submit deals with details like the game’s title, platform, and discounted price. We also have a dedicated community page where members help each other with game walkthroughs, tips, and strategies.

You can create a wishlist and get notified when any of your desired games go on sale. Once a deal expires, 
it’s removed, keeping the listings current. Our goal is to help you save money, discover new titles, and connect 
with other gamers. Enjoy the hunt!</p>
    </div>
</body>
</html>
<?php include("footer.php"); ?>