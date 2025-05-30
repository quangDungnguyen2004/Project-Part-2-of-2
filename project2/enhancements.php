<!DOCTYPE html>
<html lang="en">

<?php
    include "header.inc";
?>

<body>
    <?php
    include "nav.inc";
    ?>
    <h2>Tri Enhancements</h2>
    <ul>
        <li>Allow managers to choose which field to use for sorting EOI records in the display order on the
            <code>manage.php</code> page.
        </li>
        <li>Enable managers to modify and remove data entries within <code>manage.php</code>.</li>
        <li>Implement a manager registration page (<code>register.php</code>) that uses server-side validation to ensure
            usernames are unique and passwords meet specific criteria, saving this data in a dedicated table.</li>
        <li>Develop a login page (<code>login.php</code>) for managers, verifying credentials against stored usernames
            and
            passwords.</li>
        <li>Restrict website access for a set period after three consecutive failed login attempts
            (<code>login.php</code>).
        </li>
        <li>Display the user profile image in the top navigation bar when logged in, and show a dropdown menu with
            "Manage"
            and "Logout" options when the profile picture is tapped.</li>
        <li>Use prepared statements in <code>login.php</code>, <code>register.php</code>, and
            <code>process_eoi.php</code>
            to enhance security.</li>
        <li>Normalize the database by creating separate tables for people, skills, and people_skills, and join these
            tables
            in <code>manage.php</code> to display each person’s skills.</li>
    </ul>
    <h2>Dung Enhancements</h2>
    <ul>
        <li>Allow Manager to add a new job into the job list
            <code>addjob.php</code> page.
        </li>
        <li>Process the job and add to the database <code>processjob.php</code>.</li>
        <li>Job list in <code>apply.php</code> now include both the new job and old jobs</li>
        <li>Creating a table for both jobs and requirements and connect them using one to many relationship. Normalize the data to at least 1NF.</li>
    </ul>
    <h2>Hien Enhancements</h2>
    <ul>
             <li>Allows users to review the data they entered in the previous form, and allows them to return to the form to edit information 
                 or agree to submit the form data one last time. <code>confirmation.php</code>   </li>
             <li>Secure user information by ensuring that users can only view the confirmation page through form submission 
                 and not through other sources such as URL links. <code>confirmation.php</code>   </li>   
    </ul>
    <?php
    include "footer.inc";
    ?>
</body>

</html>