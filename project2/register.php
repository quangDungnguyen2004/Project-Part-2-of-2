
<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php"); // Assumes $conn is set up here

// 1. Create users table if not exists
$createTableSQL = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTableSQL);

// 2. Initialize variables for feedback
$errors = [];
$success = false;

// 3. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // 4. Server-side validation
    if (!preg_match('/^[A-Za-z]{1,20}$/', $username)) {
        $errors[] = "Username must contain only letters (A-Z), max 20 characters.";
    }

    // Password: 8-20 chars, at least one letter and one number, only letters/numbers
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}$/', $password)) {
        $errors[] = "Password must be 8-20 characters, contain at least one letter and one number, and only letters and numbers.";
    }

    // 5. Check if username is unique
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_count);
        $stmt->fetch();
        $stmt->close();

        if ($user_count > 0) {
            $errors[] = "Username already exists. Please choose another.";
        }
    }

    // 6. If no errors, insert user
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password_hash);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Registration failed. Please try again later.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "header.inc"; ?>

<body>
    <?php include "nav.inc"; ?>
    <div class="login-wrapper">
        <h1 class="login-title">Register</h1>
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
                Registration successful! <a href="login.php">Login here</a>.
            </div>
        <?php endif; ?>
        <?php if (!$success): ?>
        <form method="post" autocomplete="off">
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
                    <input type="password" id="password" name="password" maxlength="20" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}$"
                        placeholder="Type here..." autocomplete="new-password" required
                        title="Password must be 8-20 characters, contain at least one letter and one number, and only letters and numbers.">
                </div>
                <input class="login-button" type="submit" value="Register"></input>
                <a href="login.php" class="register-link">Already have an account? Login here</a>
            </fieldset>
        </form>
        <?php endif; ?>
    </div>
    <?php include "footer.inc"; ?>
</body>

</html>
