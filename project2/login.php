<?php
// Initialize session for potential future use (e.g., user tracking)
session_start();

// Enable full error reporting for development/debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security check: Ensure this page is only accessed via POST method
// Prevents direct URL access
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//    header("Location: index.php");
//    exit();
//}

// Include database connection settings
require_once("settings.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono&display=swap" rel="stylesheet">
    <title>Manage</title>
</head>

<body>
    <?php include "header.inc"; ?>
    <div class="login-wrapper">
        <h1 class="login-title">Login</h1>
        <form method="post">
            <fieldset>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" maxlength="20" pattern="^[A-Za-z]{1,20}$"
                        placeholder="Type here..." autocomplete="username" required
                        title="Username must contain only letters (A-Z), max 20 characters.">
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" maxlength="20" pattern="^[A-Za-z0-9]{1,20}$"
                        placeholder="Type here..." autocomplete="current-password" required
                        title="Password must contain only letters (A-Z) and numbers (0-9), max 20 characters.">
                </div>
                <input class="login-button" type="submit" value="Login"></input>
                <a href="register.php" class="register-link">Not a member? Register here</a>
            </fieldset>
        </form>
    </div>
    <?php include "footer.inc"; ?>
</body>

</html>