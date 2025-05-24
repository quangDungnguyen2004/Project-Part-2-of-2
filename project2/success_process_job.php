<?php
session_start();
if (empty($_SESSION['add_job_successful'])) {
  header("Location: index.php");
  exit();
}
unset($_SESSION['add_job_successful']);
$reference_number = isset($_GET['refnum']) ? htmlspecialchars($_GET['refnum']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Job Added</title>
  <style>
    body {
      font-family: "Geist Mono", sans-serif;
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

    .ref-number {
      font-size: 1.2em;
      font-weight: bold;
      color: #333;
    }

    a {
      color: #0077cc;
    }
  </style>
</head>

<body>
  <div class="success-container">
    <h2>New Job Added Successfully!</h2>
    <p>Job Reference number is:</p>
    <div class="ref-number"><?= $reference_number ?></div>
    <p>Updated to the career page.</p>
    <p><a href="index.php">Return to Home Page</a></p>
  </div>
</body>

</html>