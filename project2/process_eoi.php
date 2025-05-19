
<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }

    require_once("settings.php");

    // Helper function to sanitize input
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

        // Postcode/state matching function
        function postcode_matches_state($postcode, $state) {
            $postcode = (int)$postcode;
            switch ($state) {
                case 'ACT':
                    return ($postcode >= 200 && $postcode <= 299) ||
                           ($postcode >= 2600 && $postcode <= 2639) ||
                           ($postcode >= 2900 && $postcode <= 2920);
                case 'NSW':
                    return ($postcode >= 1000 && $postcode <= 1999) ||
                           ($postcode >= 2000 && $postcode <= 2599) ||
                           ($postcode >= 2619 && $postcode <= 2898) ||
                           ($postcode >= 2921 && $postcode <= 2999);
                case 'VIC':
                    return ($postcode >= 3000 && $postcode <= 3999) ||
                           ($postcode >= 8000 && $postcode <= 8999);
                case 'QLD':
                    return ($postcode >= 4000 && $postcode <= 4999) ||
                           ($postcode >= 9000 && $postcode <= 9999);
                case 'SA':
                    return ($postcode >= 5000 && $postcode <= 5799) ||
                           ($postcode >= 5800 && $postcode <= 5999);
                case 'WA':
                    return ($postcode >= 6000 && $postcode <= 6797) ||
                           ($postcode >= 6800 && $postcode <= 6999);
                case 'TAS':
                    return ($postcode >= 7000 && $postcode <= 7799) ||
                           ($postcode >= 7800 && $postcode <= 7999);
                case 'NT':
                    return ($postcode >= 800 && $postcode <= 899) ||
                           ($postcode >= 900 && $postcode <= 999);
                default:
                    return false;
            }
        }

    // Sanitize all inputs
    $reference_number = sanitize_input($_POST['reference_number'] ?? '');
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $street = sanitize_input($_POST['street'] ?? '');
    $suburb = sanitize_input($_POST['suburb'] ?? '');
    $postcode = sanitize_input($_POST['postcode'] ?? '');
    $state = sanitize_input($_POST['state'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone_number = sanitize_input($_POST['phone_number'] ?? '');
    $tech_skills = $_POST['tech_skills'] ?? array(); // Should be an array
    $other_skills = sanitize_input($_POST['other_skills'] ?? '');

    // Validate required fields
    $errors = array();

    if (empty($reference_number)) $errors[] = "Job Reference Number is required.";
    if (empty($first_name)) $errors[] = "First Name is required.";
    if (empty($last_name)) $errors[] = "Last Name is required.";
    if (empty($street)) $errors[] = "Street Address is required.";
    if (empty($suburb)) $errors[] = "Suburb is required.";
    if (empty($postcode)) $errors[] = "Postcode is required.";
    if (empty($state)) $errors[] = "State is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($phone_number)) $errors[] = "Phone Number is required.";

    // Additional validation (regex, length, etc.)
    if (!empty($first_name) && !preg_match('/^[A-Za-z]{1,20}$/', $first_name)) $errors[] = "First Name must be 1-20 alphabetic characters.";
    if (!empty($last_name) && !preg_match('/^[A-Za-z]{1,20}$/', $last_name)) $errors[] = "Last Name must be 1-20 alphabetic characters.";
    if (!empty($postcode) && !preg_match('/^[0-9]{4}$/', $postcode)) $errors[] = "Postcode must be exactly 4 digits.";
    $valid_states = array('VIC','NSW','QLD','NT','WA','SA','TAS','ACT');
    if (!empty($state) && !in_array($state, $valid_states)) $errors[] = "Invalid state selected.";
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (!empty($phone_number) && !preg_match('/^[0-9 ]{8,12}$/', $phone_number)) $errors[] = "Phone number must be 8-12 digits or spaces.";
    
    // State/Postcode matching validation
    if (!empty($state) && !empty($postcode) && !postcode_matches_state($postcode, $state)) {
        $errors[] = "The postcode does not match the selected state.";
    }
    // If there are errors, show a user-friendly error page and stop
    if (!empty($errors)) {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Submission Error</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f8f8f8; }
                .error-container { max-width: 500px; margin: 50px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; padding: 30px; }
                h2 { color: #c00; }
                ul { color: #c00; }
                a { color: #0077cc; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h2>There were errors in your submission:</h2>
                <ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>
                <p><a href='apply.php'>Try again</a></p>
            </div>
        </body>
        </html>";
        exit();
    }

    // Table creation statements
    $createPeopleTable = "CREATE TABLE IF NOT EXISTS people (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        JobRefNumber VARCHAR(20) NOT NULL,
        FirstName VARCHAR(20) NOT NULL CHECK (FirstName REGEXP '^[A-Za-z]{1,20}$'),
        LastName VARCHAR(20) NOT NULL CHECK (LastName REGEXP '^[A-Za-z]{1,20}$'),
        StreetAddress VARCHAR(40) NOT NULL,
        Suburb VARCHAR(40) NOT NULL,
        State ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
        Postcode CHAR(4) NOT NULL CHECK (Postcode REGEXP '^[0-9]{4}$'),
        Email VARCHAR(100) NOT NULL,
        Phone VARCHAR(15) NOT NULL CHECK (Phone REGEXP '^[0-9 ]{8,12}$'),
        OtherSkills TEXT,
        Status ENUM('New', 'Current', 'Final') DEFAULT 'New'
    )";

    $insertSkills = "INSERT IGNORE INTO skills (SkillName) VALUES
        ('Python'),
        ('R'),
        ('Julia'),
        ('Java'),
        ('C++')";

    $createSkillsTable = "CREATE TABLE IF NOT EXISTS skills (
        SkillID INT AUTO_INCREMENT PRIMARY KEY,
        SkillName VARCHAR(100) NOT NULL UNIQUE
    )";

    $createPeopleSkillsTable = "CREATE TABLE IF NOT EXISTS people_skills (
        EOInumber INT NOT NULL,
        SkillID INT NOT NULL,
        PRIMARY KEY (EOInumber, SkillID),
        FOREIGN KEY (EOInumber) REFERENCES people(EOInumber) ON DELETE CASCADE,
        FOREIGN KEY (SkillID) REFERENCES skills(SkillID) ON DELETE CASCADE
    )";

    // Execute table creation and skill insertion
    mysqli_query($conn, $createPeopleTable);
    mysqli_query($conn, $createSkillsTable);
    mysqli_query($conn, $createPeopleSkillsTable);
    mysqli_query($conn, $insertSkills);

    // Insert data into the people table
    $insertPeople = "INSERT INTO people (JobRefNumber, FirstName, LastName, StreetAddress, Suburb, State, Postcode, Email, Phone, OtherSkills) 
        VALUES ('$reference_number', '$first_name', '$last_name', '$street', '$suburb', '$state', '$postcode', '$email', '$phone_number', '$other_skills')";
    if (mysqli_query($conn, $insertPeople)) {
        $eoinumber = mysqli_insert_id($conn);
        // Insert data into the people_skills table
        foreach ($tech_skills as $skill) {
            $skill = sanitize_input($skill);
            $insertPeopleSkills = "INSERT INTO people_skills (EOInumber, SkillID) 
                SELECT $eoinumber, SkillID FROM skills WHERE SkillName = '$skill'";
            mysqli_query($conn, $insertPeopleSkills);
        }
        // Show confirmation page (no JavaScript)
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Application Submitted</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f8f8f8; }
                .success-container { max-width: 500px; margin: 50px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; padding: 30px; text-align: center; }
                h2 { color: #007700; }
                .eoi-number { font-size: 1.2em; font-weight: bold; color: #333; }
                a { color: #0077cc; }
            </style>
        </head>
        <body>
            <div class='success-container'>
                <h2>Application Submitted Successfully!</h2>
                <p>Your EOI number is:</p>
                <div class='eoi-number'>{$eoinumber}</div>
                <p>Thank you for your application.</p>
                <p><a href='index.php'>Return to Home Page</a></p>
            </div>
        </body>
        </html>";
    } else {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Submission Error</title>
        </head>
        <body>
            <h2>Error: " . htmlspecialchars(mysqli_error($conn)) . "</h2>
            <p><a href='apply.php'>Try again</a></p>
        </body>
        </html>";
    }
?>
