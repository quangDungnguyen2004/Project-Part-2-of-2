<?php
session_start();
if (empty($_SESSION['eoi_success'])) {
    header("Location: index.php");
    exit();
}
unset($_SESSION['eoi_success']);
$eoinumber = isset($_GET['eoi']) ? htmlspecialchars($_GET['eoi']) : '';
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <title>Application Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
        }

        .success-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ccc;
            padding: 30px;
            text-align: center;
        }

        h2 {
            color: #007700;
        }

        .eoi-number {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }

        a {
            color: #0077cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class='success-container'>
        <h2>Application Submitted Successfully!</h2>
        <p>Your Expression of Interest (EOI) number is:</p>
        <div class='eoi-number'><?= $eoinumber ?></div>
        <p>Please keep this number for your records. We will contact you shortly regarding your application.</p>
        <p><a href='index.php'>Return to Home Page</a></p>
    </div>
</body>

</html>