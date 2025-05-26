
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php");
$messages = [];

// SECTION 1: HANDLE POST REQUESTS

if (isset($_POST['delete_jobref']) && !empty($_POST['delete_jobref'])) {
    $jobref_delete = mysqli_real_escape_string($conn, $_POST['delete_jobref']);
    $delete_result = mysqli_query($conn, "DELETE FROM people WHERE JobRefNumber='$jobref_delete'");

    if ($delete_result) {
        $deleted = mysqli_affected_rows($conn);
        $messages[] = "Deleted $deleted EOIs for Job Reference: $jobref_delete";
    } else {
        $messages[] = "Error deleting EOIs for Job Reference: $jobref_delete";
    }
}

if (isset($_POST['change_status_eoi']) && isset($_POST['status'])) {
    $eoi_id = intval($_POST['change_status_eoi']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $update_result = mysqli_query($conn, "UPDATE people SET Status='$new_status' WHERE EOInumber=$eoi_id");

    if ($update_result) {
        $messages[] = "Status updated for EOI $eoi_id to '$new_status'";
    } else {
        $messages[] = "Error updating status for EOI $eoi_id";
    }
}

// SECTION 2: DATA PREPARATION

$skills_result = mysqli_query($conn, "SELECT SkillID, SkillName FROM skills ORDER BY SkillID");
$skills = [];
while ($row = mysqli_fetch_assoc($skills_result)) {
    $skills[] = $row;
}

$jobref_result = mysqli_query($conn, "SELECT DISTINCT JobRefNumber FROM people ORDER BY JobRefNumber");
$jobrefs = [];
while ($row = mysqli_fetch_assoc($jobref_result)) {
    $jobrefs[] = $row['JobRefNumber'];
}

// SECTION 3: HANDLE FILTER PARAMETERS

$first_name_search = isset($_GET['first_name']) ? trim($_GET['first_name']) : "";
$last_name_search = isset($_GET['last_name']) ? trim($_GET['last_name']) : "";
$selected_jobref = isset($_GET['jobref']) ? $_GET['jobref'] : "ALL";

// --- Sorting additions ---
$sortable_fields = [
    'EOInumber' => 'EOI Number',
    'FirstName' => 'First Name',
    'LastName' => 'Last Name',
    'JobRefNumber' => 'Job Ref',
    'Status' => 'Status'
];
$sort_field = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'EOInumber';
$sort_dir = isset($_GET['sort_dir']) && strtolower($_GET['sort_dir']) === 'desc' ? 'DESC' : 'ASC';

// Validate sort field
if (!array_key_exists($sort_field, $sortable_fields)) {
    $sort_field = 'EOInumber';
}

// Build SQL WHERE clause dynamically based on filters
$where = [];
if ($selected_jobref !== "ALL" && $selected_jobref !== "") {
    $safe_jobref = mysqli_real_escape_string($conn, $selected_jobref);
    $where[] = "JobRefNumber='$safe_jobref'";
}
if ($first_name_search !== "") {
    $safe_first = mysqli_real_escape_string($conn, $first_name_search);
    $where[] = "FirstName LIKE '%$safe_first%'";
}
if ($last_name_search !== "") {
    $safe_last = mysqli_real_escape_string($conn, $last_name_search);
    $where[] = "LastName LIKE '%$safe_last%'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// --- Add ORDER BY clause ---
$order_sql = "ORDER BY $sort_field $sort_dir";

// Execute the main query with all filters and sorting
$people_result = mysqli_query($conn, "SELECT * FROM people $where_sql $order_sql");

// Create a lookup map for people's skills
$person_skill_map = [];
$pks_result = mysqli_query($conn, "SELECT EOInumber, SkillID FROM people_skills");
while ($row = mysqli_fetch_assoc($pks_result)) {
    $person_skill_map[$row['EOInumber']][] = $row['SkillID'];
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "header.inc"; ?>

<body>
    <?php include "nav.inc"; ?>
    <?php
    // SECTION 4: PAGE LAYOUT
    ?>

    <div class="dashboard-header">
        <h1>Dashboard</h1>
    </div>

    <?php if (!empty($messages)): ?>
        <div style="color: darkgreen;">
            <?php foreach ($messages as $msg): ?>
                <p><?= htmlspecialchars($msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="db_manipulation">
        <h2>Action Center</h2>
        <form class="add-job-redirect" action="addjob.php" method="post">
            <fieldset>
                <label>Publishing New Jobs:</label>
                <input class="add-job-button" type="submit" value="Add Job">
            </fieldset>
        </form>
        <!-- Search/Filter form -->
        <form method="get">
            <fieldset>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name"
                    value="<?= htmlspecialchars($first_name_search) ?>">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($last_name_search) ?>">

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

                <!-- Sort field dropdown -->
                <label for="sort_field">Sort By:</label>
                <select id="sort_field" name="sort_field" onchange="this.form.submit()">
                    <?php foreach ($sortable_fields as $field => $label): ?>
                        <option value="<?= $field ?>" <?= ($sort_field == $field) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select id="sort_dir" name="sort_dir" onchange="this.form.submit()">
                    <option value="asc" <?= ($sort_dir == 'ASC') ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= ($sort_dir == 'DESC') ? 'selected' : '' ?>>Descending</option>
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
                    <?php foreach ($skills as $skill): ?>
                        <th><?= htmlspecialchars($skill['SkillName']) ?></th>
                    <?php endforeach; ?>
                    <th>Other Skills</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($person = mysqli_fetch_assoc($people_result)): ?>
                    <tr>
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
                        <?php
                        $person_skills = $person_skill_map[$person['EOInumber']] ?? [];
                        foreach ($skills as $skill) {
                            $has_skill = in_array($skill['SkillID'], $person_skills) ? "X" : "";
                            echo "<td style='text-align:center;'>$has_skill</td>";
                        }
                        ?>
                        <td><?= htmlspecialchars($person['OtherSkills']) ?></td>
                        <td><?= htmlspecialchars($person['Status']) ?></td>
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
