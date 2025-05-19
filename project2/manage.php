<?php
// Start the session - required for maintaining user state across pages
session_start();

// Enable all PHP error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and configuration
require_once("settings.php");
// Initialize array to store status/error messages
$messages = [];

// SECTION 1: HANDLE POST REQUESTS

// Handle mass deletion of EOIs by Job Reference Number
if (isset($_POST['delete_jobref']) && !empty($_POST['delete_jobref'])) {
    // Sanitize input to prevent SQL injection
    $jobref_delete = mysqli_real_escape_string($conn, $_POST['delete_jobref']);
    // Execute DELETE query for all EOIs with matching job reference
    $delete_result = mysqli_query($conn, "DELETE FROM people WHERE JobRefNumber='$jobref_delete'");

    if ($delete_result) {
        // Get number of rows affected by DELETE
        $deleted = mysqli_affected_rows($conn);
        $messages[] = "Deleted $deleted EOIs for Job Reference: $jobref_delete";
    } else {
        $messages[] = "Error deleting EOIs for Job Reference: $jobref_delete";
    }
}

// Handle updating the status of individual EOIs
if (isset($_POST['change_status_eoi']) && isset($_POST['status'])) {
    // Convert EOI number to integer for safety
    $eoi_id = intval($_POST['change_status_eoi']);
    // Sanitize status input
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    // Execute UPDATE query
    $update_result = mysqli_query($conn, "UPDATE people SET Status='$new_status' WHERE EOInumber=$eoi_id");

    if ($update_result) {
        $messages[] = "Status updated for EOI $eoi_id to '$new_status'";
    } else {
        $messages[] = "Error updating status for EOI $eoi_id";
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
    <?php
    // SECTION 2: DATA PREPARATION
    
    // Fetch all skills for displaying in table headers
    $skills_result = mysqli_query($conn, "SELECT SkillID, SkillName FROM skills ORDER BY SkillID");
    $skills = [];
    while ($row = mysqli_fetch_assoc($skills_result)) {
        $skills[] = $row;
    }

    // Get unique job reference numbers for dropdown filters
    $jobref_result = mysqli_query($conn, "SELECT DISTINCT JobRefNumber FROM people ORDER BY JobRefNumber");
    $jobrefs = [];
    while ($row = mysqli_fetch_assoc($jobref_result)) {
        $jobrefs[] = $row['JobRefNumber'];
    }

    // SECTION 3: HANDLE FILTER PARAMETERS
    
    // Get and sanitize search parameters from GET request
    $first_name_search = isset($_GET['first_name']) ? trim($_GET['first_name']) : "";
    $last_name_search = isset($_GET['last_name']) ? trim($_GET['last_name']) : "";
    $selected_jobref = isset($_GET['jobref']) ? $_GET['jobref'] : "ALL";

    // Build SQL WHERE clause dynamically based on filters
    $where = [];
    // Add job reference filter if not showing all
    if ($selected_jobref !== "ALL" && $selected_jobref !== "") {
        $safe_jobref = mysqli_real_escape_string($conn, $selected_jobref);
        $where[] = "JobRefNumber='$safe_jobref'";
    }
    // Add first name filter if provided
    if ($first_name_search !== "") {
        $safe_first = mysqli_real_escape_string($conn, $first_name_search);
        $where[] = "FirstName LIKE '%$safe_first%'";
    }
    // Add last name filter if provided
    if ($last_name_search !== "") {
        $safe_last = mysqli_real_escape_string($conn, $last_name_search);
        $where[] = "LastName LIKE '%$safe_last%'";
    }
    // Combine all WHERE conditions with AND
    $where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    // Execute the main query with all filters
    $people_result = mysqli_query($conn, "SELECT * FROM people $where_sql");

    // Create a lookup map for people's skills
    // Format: [EOInumber => [SkillID1, SkillID2, ...]]
    $person_skill_map = [];
    $pks_result = mysqli_query($conn, "SELECT EOInumber, SkillID FROM people_skills");
    while ($row = mysqli_fetch_assoc($pks_result)) {
        $person_skill_map[$row['EOInumber']][] = $row['SkillID'];
    }
    ?>

    <!-- SECTION 4: PAGE LAYOUT -->

    <div class="dashboard-header">
        <h1>Dashboard</h1>
    </div>

    <!-- Display success/error messages if any -->
    <?php if (!empty($messages)): ?>
        <div style="color: darkgreen;">
            <?php foreach ($messages as $msg): ?>
                <p><?= htmlspecialchars($msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="db_manipulation">
        <h2>Action Center</h2>
        <!-- Search/Filter form -->
        <form method="get">
        <fieldset>
            <!-- Name filters -->
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($first_name_search) ?>">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($last_name_search) ?>">

            <!-- Job Reference filter dropdown -->
            <label for="jobref">Job Reference:</label>
            <select id="jobref" name="jobref" onchange="this.form.submit()">
                <option value="ALL" <?= ($selected_jobref === "ALL") ? ' selected' : '' ?>>
                    Show All
                </option>
                <?php foreach ($jobrefs as $jobref): ?>
                    <option value="<?= htmlspecialchars($jobref) ?>" <?= ($selected_jobref == $jobref) ? ' selected' : '' ?>>
                        <?= htmlspecialchars($jobref) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input class="search-button" type="submit" value="Search">
        </fieldset>
        </form>

        <!-- Mass deletion form -->
        <form method="post"
            onsubmit="return confirm('Are you sure you want to delete all EOIs for this Job Reference? This cannot be undone.');">
            <label for="delete_jobref">Delete All EOIs for Job Reference:</label>
            <select name="delete_jobref" id="delete_jobref" required>
                <option value="">Select...</option>
                <?php foreach ($jobrefs as $jobref): ?>
                    <option value="<?= htmlspecialchars($jobref) ?>">
                        <?= htmlspecialchars($jobref) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input class="confirm-button" type="submit" value="Confirm">
        </form>
    </div>

    <!-- SECTION 5: DATA DISPLAY TABLE -->
    <div>
        <table border="1" cellpadding="5" cellspacing="0">
            <!-- Table Headers -->
            <thead>
                <tr>
                    <th>EOI Number</th>
                    <th>Job Ref</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Street</th>
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Postcode</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <!-- Dynamic skill columns -->
                    <?php foreach ($skills as $skill): ?>
                        <th><?= htmlspecialchars($skill['SkillName']) ?></th>
                    <?php endforeach; ?>
                    <th>Other Skills</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <!-- Table Data -->
            <tbody>
                <?php while ($person = mysqli_fetch_assoc($people_result)): ?>
                    <tr>
                        <!-- Basic person information -->
                        <td><?= $person['EOInumber'] ?></td>
                        <td><?= htmlspecialchars($person['JobRefNumber']) ?></td>
                        <td><?= htmlspecialchars($person['FirstName']) ?></td>
                        <td><?= htmlspecialchars($person['LastName']) ?></td>
                        <td><?= htmlspecialchars($person['StreetAddress']) ?></td>
                        <td><?= htmlspecialchars($person['Suburb']) ?></td>
                        <td><?= htmlspecialchars($person['State']) ?></td>
                        <td><?= htmlspecialchars($person['Postcode']) ?></td>
                        <td><?= htmlspecialchars($person['Email']) ?></td>
                        <td><?= htmlspecialchars($person['Phone']) ?></td>

                        <!-- Skills checkmarks -->
                        <?php
                        $person_skills = $person_skill_map[$person['EOInumber']] ?? [];
                        foreach ($skills as $skill) {
                            $has_skill = in_array($skill['SkillID'], $person_skills) ? "X" : "";
                            echo "<td style='text-align:center;'>$has_skill</td>";
                        }
                        ?>

                        <td><?= htmlspecialchars($person['OtherSkills']) ?></td>
                        <td><?= htmlspecialchars($person['Status']) ?></td>

                        <!-- Status update form -->
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="change_status_eoi" value="<?= $person['EOInumber'] ?>">
                                <select name="status">
                                    <?php foreach (['New', 'Current', 'Final'] as $status_option): ?>
                                        <option value="<?= $status_option ?>" <?= $person['Status'] == $status_option ? ' selected' : '' ?>>
                                            <?= $status_option ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="submit">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include "footer.inc"; ?>
</body>

</html>