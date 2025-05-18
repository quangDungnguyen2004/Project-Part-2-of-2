<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: jobs.php");
        exit();
    }

    require_once("settings.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            echo "<script>alert('Application submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
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
    <title>Application Form</title>
</head>
<body>
    <?php
        include "nav.inc";
    ?>
    <main class="main">
        <div class="form-wrapper">
            <form class="application-form" action="" method="post">
                <h2 class="form-title">Application Form</h2>
                <p class="under-form-title">Application form for Employee</p>
                <div class="input-block">
                    <!-- Job reference number -->
                    <label for="refnum">Reference Number</label>
                    <select class="select-box" name="reference_number" id="refnum" required>
                        <option value="" selected disabled>Select Reference ID</option>
                        <option value="FT102">FT102</option>
                        <option value="FT104">FT104</option>
                        <option value="FT105">FT105</option>
                        <option value="FT105">FT106</option>
                    </select>
                </div>
                <div class="input-row">
                    <div class="input-block">
                        <!-- First name -->
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="first_name" maxlength="20" pattern="^[A-Za-z]{1,20}$" placeholder="Type here..." autocomplete="given-name" required title="First name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <!-- Last name -->
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="last_name" maxlength="20" pattern="^[A-Za-z]{1,20}$" placeholder="Type here..." autocomplete="family-name" required title="Last name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <!-- Date of Birth -->
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="date_of_birth" required>
                    </div>
                </div>
                <div class="input-block">
                    <fieldset>
                        <!-- Gender -->
                        <legend>Gender</legend>
                        <div class="gender-selection-wrapper">
                            <div>
                                <input type="radio" id="male" name="gender" value="Male" checked required>
                                <label for="male">Male</label>
                            </div>
                            <div>
                                <input type="radio" id="female" name="gender" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div>
                                <input type="radio" id="other" name="gender" value="Other">
                                <label for="other">Others</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <fieldset>
                        <legend>Address Details</legend>
                        <div class="address-option">
                            <!-- Street address -->
                            <label for="street">Street Address</label>
                            <input type="text" id="street" name="street" maxlength="40" placeholder="Enter your street address" required>
                        </div>
                        <div class="address-option">
                            <!-- Suburb/Town -->
                            <label for="suburb">Suburb/Town</label>
                            <input type="text" id="suburb" name="suburb" maxlength="40" placeholder="Enter your suburb/town" required>
                        </div>
                        <div class="address-option">
                            <!-- Postcode -->
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" maxlength="4" pattern="020\d|02[1-9]\d|0[3-9]\d{2}|[1-8]\d{3}|9[0-8]\d{2}|99[0-3]\d|994[0-4]" placeholder="Enter your postcode" required title="Please enter a valid 4-digit Australian postcode between 0200 and 9944">
                        </div>
                        <div class="address-option">
                            <!-- State -->
                            <label for="state">State</label>
                            <select class="select-box" name="state" id="state" required>
                                <option value="">Select your state</option>
                                <option value="VIC">VIC</option>
                                <option value="NSW">NSW</option>
                                <option value="QLD">QLD</option>
                                <option value="NT">NT</option>
                                <option value="WA">WA</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="ACT">ACT</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <!-- Email -->
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" autocomplete="email" required>
                </div>
                <div class="input-block">
                    <!-- Phone number -->
                    <label for="phone">Phone Number</label>
                    <!--Pattern from 3Widgets.com Regex generator-->
                    <input type="tel" id="phone" name="phone_number" maxlength="12" pattern="04[0-9]{2} [0-9]{3} [0-9]{3}" placeholder="0412 345 678" title="Format: 0412 345 678" autocomplete="tel" required>
                </div>
                <div class="input-block">
                    <fieldset class="skills-section">
                        <!-- Technical Skills -->
                        <legend>Required Technical Skills</legend>
                        <div class="skill-option">
                            <input type="checkbox" id="Python" name="tech_skills" value="Python" required>
                            <label for="Python">Python</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="R" name="tech_skills" value="R">
                            <label for="R">R</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="Julia" name="tech_skills" value="Julia">
                            <label for="Julia">Julia</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="Java" name="tech_skills" value="Java">
                            <label for="Java">Java</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="C++" name="tech_skills" value="C++">
                            <label for="C++">C++</label>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <!-- Other skills -->
                    <label for="other_skills">Other Skills</label>
                    <textarea id="other_skills" name="other_skills" rows="4" cols="40" placeholder="List any other skills"></textarea>
                </div>
                <div class="input-block">
                    <input class="left-button" type="reset" value="Reset">
                    <input class="right-button" type="submit" value="Apply">
                </div>
            </form>
        </div>
    </main>
    <?php
        include "footer.inc";
    ?>
</body>
</html>
<!--
Pattern for postcode and phone number were taken from 3widgets.com
-->