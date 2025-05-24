<?php
// Initialize session for potential future use (e.g., user tracking)
session_start();

// Enable full error reporting for development/debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security check: Ensure this page is only accessed via POST method
// Prevents direct URL access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Include database connection settings
require_once("settings.php");

/**
 * Sanitizes user input to prevent XSS attacks
 * - Removes whitespace
 * - Removes backslashes
 * - Converts special characters to HTML entities
 */
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validates if a postcode matches the specified state
 * Returns true if the postcode is valid for the given state
 * Based on Australia Post state/territory postcode ranges
 */
function postcode_matches_state($postcode, $state)
{
    $postcode = (int) $postcode;
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

// SECTION 1: INPUT PROCESSING
// Sanitize all form inputs using the sanitize_input function
// The ?? operator provides a default empty value if the POST value doesn't exist
$reference_number = sanitize_input($_POST['reference_number'] ?? '');
$first_name = sanitize_input($_POST['first_name'] ?? '');
$last_name = sanitize_input($_POST['last_name'] ?? '');
$street = sanitize_input($_POST['street'] ?? '');
$suburb = sanitize_input($_POST['suburb'] ?? '');
$postcode = sanitize_input($_POST['postcode'] ?? '');
$state = sanitize_input($_POST['state'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone_number = sanitize_input($_POST['phone_number'] ?? '');
// Tech skills is an array, so we don't sanitize it here
$tech_skills = $_POST['tech_skills'] ?? array();
$other_skills = sanitize_input($_POST['other_skills'] ?? '');

// SECTION 2: VALIDATION
// Initialize array to store validation errors
$errors = array();

// Check for required fields
// Each empty field adds an error message to the errors array
if (empty($reference_number))
    $errors[] = "Job Reference Number is required.";
if (empty($first_name))
    $errors[] = "First Name is required.";
if (empty($last_name))
    $errors[] = "Last Name is required.";
if (empty($street))
    $errors[] = "Street Address is required.";
if (empty($suburb))
    $errors[] = "Suburb is required.";
if (empty($postcode))
    $errors[] = "Postcode is required.";
if (empty($state))
    $errors[] = "State is required.";
if (empty($email))
    $errors[] = "Email is required.";
if (empty($phone_number))
    $errors[] = "Phone Number is required.";

// Perform detailed validation with regex patterns
// Name validation: only letters, 1-20 characters
if (!empty($first_name) && !preg_match('/^[A-Za-z]{1,20}$/', $first_name))
    $errors[] = "First Name must be 1-20 alphabetic characters.";
if (!empty($last_name) && !preg_match('/^[A-Za-z]{1,20}$/', $last_name))
    $errors[] = "Last Name must be 1-20 alphabetic characters.";

// Postcode validation: exactly 4 digits
if (!empty($postcode) && !preg_match('/^[0-9]{4}$/', $postcode))
    $errors[] = "Postcode must be exactly 4 digits.";

// State validation: must be one of the valid Australian states/territories
$valid_states = array('VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT');
if (!empty($state) && !in_array($state, $valid_states))
    $errors[] = "Invalid state selected.";

// Email validation using PHP's built-in filter
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Invalid email format.";

// Phone validation: 8-12 digits or spaces
if (!empty($phone_number) && !preg_match('/^[0-9 ]{8,12}$/', $phone_number))
    $errors[] = "Phone number must be 8-12 digits or spaces.";

// Check if postcode matches the selected state
if (!empty($state) && !empty($postcode) && !postcode_matches_state($postcode, $state)) {
    $errors[] = "The postcode does not match the selected state.";
}

// SECTION 3: ERROR HANDLING
// If there are validation errors, display them and stop processing
if (!empty($errors)) {
    // Output styled error page
    echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Submission Error</title>
            <style>
                body { font-family: 'Geist Mono', sans-serif; background: #f8f8f8; }
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

// SECTION 4: DATABASE SCHEMA
// SQL statements for creating necessary tables if they don't exist

// People table - stores main applicant information
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

// Pre-defined skills to be inserted
$insertSkills = "INSERT IGNORE INTO skills (SkillName) VALUES
        ('Python'),
        ('R'),
        ('Julia'),
        ('Java'),
        ('C++')";

// Skills table - stores available technical skills
$createSkillsTable = "CREATE TABLE IF NOT EXISTS skills (
        SkillID INT AUTO_INCREMENT PRIMARY KEY,
        SkillName VARCHAR(100) NOT NULL UNIQUE
    )";

// Junction table for many-to-many relationship between people and skills
$createPeopleSkillsTable = "CREATE TABLE IF NOT EXISTS people_skills (
        EOInumber INT NOT NULL,
        SkillID INT NOT NULL,
        PRIMARY KEY (EOInumber, SkillID),
        FOREIGN KEY (EOInumber) REFERENCES people(EOInumber) ON DELETE CASCADE,
        FOREIGN KEY (SkillID) REFERENCES skills(SkillID) ON DELETE CASCADE
    )";

// SECTION 5: DATABASE OPERATIONS
// Execute table creation and initial data insertion
mysqli_query($conn, $createPeopleTable);
mysqli_query($conn, $createSkillsTable);
mysqli_query($conn, $createPeopleSkillsTable);
mysqli_query($conn, $insertSkills);

// Insert new applicant using prepared statement (prevents SQL injection)
$stmt = mysqli_prepare($conn, "INSERT INTO people (JobRefNumber, FirstName, LastName, StreetAddress, Suburb, State, Postcode, Email, Phone, OtherSkills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssssssss", $reference_number, $first_name, $last_name, $street, $suburb, $state, $postcode, $email, $phone_number, $other_skills);

// SECTION 6: FINAL PROCESSING AND RESPONSE
if (mysqli_stmt_execute($stmt)) {
    // Get the ID of the newly inserted person
    $eoinumber = mysqli_insert_id($conn);

    // Insert the selected technical skills
    $skill_stmt = mysqli_prepare($conn, "INSERT INTO people_skills (EOInumber, SkillID) SELECT ?, SkillID FROM skills WHERE SkillName = ?");
    foreach ($tech_skills as $skill) {
        $skill = sanitize_input($skill);
        mysqli_stmt_bind_param($skill_stmt, "is", $eoinumber, $skill);
        mysqli_stmt_execute($skill_stmt);
    }

    // Redirect to success page with EOI number
    $_SESSION['eoi_success'] = true;
    header("Location: success_process_eoi.php?eoi=" . urlencode($eoinumber));
    exit();
} else {
    // Display error message if database insertion fails
    echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Submission Error</title>
            <style>
                body { font-family: 'Geist Mono', sans-serif; background: #f8f8f8; }
                .error-container { max-width: 500px; margin: 50px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; padding: 30px; }
                h2 { color: #c00; }
                a { color: #0077cc; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h2>Error: " . htmlspecialchars(mysqli_error($conn)) . "</h2>
                <p><a href='apply.php'>Try again</a></p>
            </div>
        </body>
        </html>";
}
?>