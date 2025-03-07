<?php
session_start();
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            font-family: 'VT323', monospace;
            padding: 10px;
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
        p {
            font-size: 18px;
            color: #F8F8FF;
            line-height: 2;
            margin: 0 10px;
            font-weight: 300;
            font-size: 1.2rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Privacy Policy</h1>
    <p>At Gameloot, your privacy is our priority. We are committed to protecting your personal information and using it responsibly. We collect data to enhance your experience and improve our services. Your information is never shared with third parties without your consent. By using our platform, you agree to this privacy policy.</p>
</div>
</body>
</html>
<?php include("footer.php"); ?>