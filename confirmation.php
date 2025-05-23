<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $_SESSION['form_data'] = $_POST;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <style>
        body {
            background-color: #0a0a0a;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: #dddddd;
            padding: 30px;
            border-radius: 12px;
            
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid #aaa;
        }

        th {
            width: 35%;
            font-weight: bold;
            color: #222;
        }

        td {
            color: #333;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        input[type="submit"] {
            background-color: #b6e0ea;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            
        }

        

        
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirm Your Application</h2>
        <table>
            <tr><th>Reference Number</th><td><?= htmlspecialchars($_POST['Reference_number']) ?></td></tr>
            <tr><th>First Name</th><td><?= htmlspecialchars($_POST['Full_name']) ?></td></tr>
            <tr><th>Last Name</th><td><?= htmlspecialchars($_POST['Last_name']) ?></td></tr>
            <tr><th>Date of Birth</th><td><?= htmlspecialchars($_POST['Date_of_birth']) ?></td></tr>
            <tr><th>Gender</th><td><?= htmlspecialchars($_POST['Gender']) ?></td></tr>
            <tr><th>Street</th><td><?= htmlspecialchars($_POST['Street']) ?></td></tr>
            <tr><th>Suburb</th><td><?= htmlspecialchars($_POST['Suburb']) ?></td></tr>
            <tr><th>Postcode</th><td><?= htmlspecialchars($_POST['Postcode']) ?></td></tr>
            <tr><th>State</th><td><?= htmlspecialchars($_POST['State']) ?></td></tr>
            <tr><th>Email</th><td><?= htmlspecialchars($_POST['Email']) ?></td></tr>
            <tr><th>Phone</th><td><?= htmlspecialchars($_POST['Phone_number']) ?></td></tr>
            <tr><th>Technical Skills</th><td><?= isset($_POST['Tech_skills']) ? implode(", ", (array)$_POST['Tech_skills']) : '' ?></td></tr>
            <tr><th>Other Skills</th><td><?= htmlspecialchars($_POST['Other_skills']) ?></td></tr>
        </table>

        <form action="process_eoi.php" method="post" class="buttons">
            <input type="submit" value="Confirm and Submit">
        </form>
        <div class="buttons">
            <a href="apply.php"> Go Back and Edit</a>
        </div>
    </div>
</body>
</html>
<?php
}
?>