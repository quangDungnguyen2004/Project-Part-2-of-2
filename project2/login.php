
<?php
// Initialize session for potential future use (e.g., user tracking)
session_start();

// Enable full error reporting for development/debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection settings
require_once("settings.php");

// Initialize variables for feedback
$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input format
    if (!preg_match('/^[A-Za-z]{1,20}$/', $username)) {
        $errors[] = "Username must contain only letters (A-Z), max 20 characters.";
    }
    if (!preg_match('/^[A-Za-z0-9]{1,20}$/', $password)) {
        $errors[] = "Password must contain only letters (A-Z) and numbers (0-9), max 20 characters.";
    }

    if (empty($errors)) {
        // Fetch user from database
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($password_hash);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $password_hash)) {
                $success = true;
                $_SESSION['username'] = $username;
                // Redirect or show success message
                // header("Location: dashboard.php"); exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "Username not found.";
        }
        $stmt->close();
    }
}
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
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="success-message">
                Login successful! Welcome, <?= htmlspecialchars($username) ?>.
                <!-- Optionally redirect after a short delay -->
                <meta http-equiv="refresh" content="2;url=index.php">
            </div>
        <?php endif; ?>
        <?php if (!$success): ?>
        <form method="post">
            <fieldset>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" maxlength="20" pattern="^[A-Za-z]{1,20}$"
                        placeholder="Type here..." autocomplete="username" required
                        title="Username must contain only letters (A-Z), max 20 characters."
                        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
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
        <?php endif; ?>
    </div>
    <?php include "footer.inc"; ?>
</body>

</html>
