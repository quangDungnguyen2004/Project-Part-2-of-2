<?php

session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}


require_once("settings.php");

?>
<!DOCTYPE html>
<html lang="en">

    <?php
    include "header.inc";
    ?>

<body>
    <?php
    include "header.inc";
    ?>
    <main class="main">
        <div class="form-wrapper">
            <form class="application-form" action="processjob.php" method="post" novalidate="novalidate">
                <h2 class="form-title">New Job Page</h2>
                <p class="under-form-title">Application form for HR Manager</p>
                <div class="input-block">
                    <!-- Job reference number -->
                    <label for="refnum">Reference Number</label>
                    <input type="text" id="refnum" name="reference_number" maxlength="11"
                        pattern="/^[A-Z]{2}[0-9]{4,9}/" placeholder="Enter Reference Number"
                        title="Reference number should be two letter follow up by 4 to 9 numbers." required>
                    <div class="input-block">
                        <!-- job title -->
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title" placeholder="Enter the job title" required>
                    </div>
                    <div class="input-block">
                        <!-- location -->
                        <label for="location">Location</label>

                        <input type="text" id="location" name="location" placeholder="Enter the location" required>
                    </div>
                    <div class="input-block">
                        <!-- Job Description -->
                        <label for="job_desc">Job Description</label>
                        <textarea id="job_desc" name="job_desc" rows="4" cols="40" placeholder="Describe the Job"
                            required></textarea>
                    </div>
                    <div class="input-block">
                        <!-- Salary -->
                        <label for="salary">Salary</label>
                        <input type="text" id="salary" name="salary" placeholder="Enter Salary Range"
                            title="Format: $140,000-$200,000" required>
                    </div>
                    <div class="input-block">
                        <!-- Hours -->
                        <label for="hours">Hours and Employment Type</label>
                        <input type="text" id="hours" name="hours"
                            placeholder="Enter Employment Type and Working Hours per week"
                            title="Format: Full-Time, 38-40 hours/week" required>
                    </div>
                    <div class="input-block">
                        <!-- Reports -->
                        <label for="reports">Person In Charge</label>
                        <input type="text" id="reports" name="reports" placeholder="Enter the Person In Charge"
                            required>
                    </div>
                    <div class="input-block">
                        <!-- Requirements -->
                        <label for="requirements">Requirement</label>
                        <textarea id="requirements" name="requirements" rows="4" cols="40"
                            placeholder="Enter Requirements, separate using (;) or (,)"
                            title="Format: Good at Python; Great Communication Skill" required></textarea>
                    </div>
                    <div class="input-block">
                        <input class="left-button" type="reset" value="Reset">
                        <input class="right-button" type="submit" value="Add Job">
                    </div>
            </form>
        </div>
    </main>
    <?php
    include "footer.inc";
    ?>
</body>

</html>