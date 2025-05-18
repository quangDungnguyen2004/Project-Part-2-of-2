<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: jobs.php");
        exit();
    }

    require_once("settings.php");

    
    $reference_number = $_POST['reference_number'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $street = $_POST['street'] ?? '';
    $suburb = $_POST['suburb'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $state = $_POST['state'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $tech_skills = $_POST['tech_skills'] ?? array(); // Should be an array
    $other_skills = $_POST['other_skills'] ?? '';

        
    // Table creation statements
    $createPeopleTable = "CREATE TABLE IF NOT EXISTS people (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        JobRefNumber ENUM('FT102','FT104','FT105','FT106') NOT NULL,
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
            $insertPeopleSkills = "INSERT INTO people_skills (EOInumber, SkillID) 
                SELECT $eoinumber, SkillID FROM skills WHERE SkillName = '$skill'";
            mysqli_query($conn, $insertPeopleSkills);
        }
        echo "<script>
        alert('Your EOI number is: {$eoinumber}\\nApplication submitted successfully!');
        window.location.href = 'index.php';
            </script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
?>
