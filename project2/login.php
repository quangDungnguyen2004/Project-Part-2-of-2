<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php");

$errors = [];
$success = false;

// --- Lockout configuration ---
define('MAX_ATTEMPTS', 3);
define('LOCKOUT_TIME', 300); // seconds (5 minutes)

// Initialize lockout tracking in session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_until'])) {
    $_SESSION['lockout_until'] = 0;
}

// Check if currently locked out
$now = time();
$locked_out = ($_SESSION['lockout_until'] > $now);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked_out) {
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
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($password_hash);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                $success = true;
                $_SESSION['username'] = $username;
                $_SESSION['login_attempts'] = 0; // Reset attempts on success
                $_SESSION['lockout_until'] = 0;
                // Optionally redirect here
                // header("Location: dashboard.php"); exit();
            } else {
                $_SESSION['login_attempts'] += 1;
                $errors[] = "Incorrect password.";
            }
        } else {
            $_SESSION['login_attempts'] += 1;
            $errors[] = "Username not found.";
        }
        $stmt->close();

        // Check if lockout should be triggered
        if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
            $_SESSION['lockout_until'] = $now + LOCKOUT_TIME;
            $locked_out = true;
        }
    }
} elseif ($locked_out && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors[] = "Too many failed login attempts. Please try again after " . ceil(($_SESSION['lockout_until'] - $now) / 60) . " minutes.";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "header.inc"; ?>

<body>
    <?php include "nav.inc"; ?>
    <div class="login-wrapper">
        <h1 class="login-title">Login</h1>
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li>
                            <?= htmlspecialchars($err) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="success-message"
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                Login successful! Welcome,
                <?= htmlspecialchars($username) ?>.
                <meta http-equiv="refresh" content="2;url=index.php">
            </div>
        <?php endif; ?>

        <?php if ($locked_out): ?>
            <div class="error-message">
                Too many failed login attempts. Please try again after
                <?= ceil(($_SESSION['lockout_until'] - $now) / 60) ?> minute(s).
            </div>
        <?php endif; ?>

        <?php if (!$success && !$locked_out): ?>
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
            <div style="margin-top:10px; color:#555;">
                <?php if ($_SESSION['login_attempts'] > 0): ?>
                    Failed attempts:
                    <?= $_SESSION['login_attempts'] ?> /
                    <?= MAX_ATTEMPTS ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include "footer.inc"; ?>
</body>

</html>