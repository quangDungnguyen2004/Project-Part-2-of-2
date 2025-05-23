<?php 

    session_start();


    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php");
        exit();
    }


    require_once("settings.php");


    function sanitize_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    //Input
    $reference_number = sanitize_input($_POST['reference_number'] ?? '');
    $job_title = sanitize_input($_POST['job_title'] ?? '');
    $location = sanitize_input($_POST['location'] ?? '');
    $job_desc = sanitize_input($_POST['job_desc'] ?? '');
    $salary = sanitize_input($_POST['salary'] ?? '');
    $hours = sanitize_input($_POST['hours'] ?? '');
    $reports = sanitize_input($_POST['reports'] ?? '');
    $req_post = $_POST['requirements'] ?? '';

    $requirements = preg_split('/;|,/', $req_post);

    //validation
    $errors = array();
    if (empty($reference_number))
        $errors[] = "Job reference number is required.";
    if (empty($job_title))
        $errors[] = "Job Title is required.";
    if (empty($location))
        $errors[] = "Location is required.";
    if (empty($job_desc))
        $errors[] = "Job Description is required.";
    if (empty($salary))
        $errors[] = "Salary is required.";
    if (empty($reports))
        $errors[] = "Reports is required.";
    if (empty($hours))
        $errors[] = "Hours is required.";
    
    //reference number format should be two letter + 4 to 9 numbers
if (!empty($reference_number) && !preg_match('/^[A-Z]{2}[0-9]{4,9}/', $reference_number))
    $errors[] = "Reference number should be two letter follow up by 4 to 9 numbers.";

    //error handling, should be similar to process_eoi.php page
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
                    <p><a href='addjob.php'>Try again</a></p>
                </div>
            </body>
            </html>";
        exit();
    }
    

    //insert into database
    $stmt = mysqli_prepare($conn, "INSERT INTO job (JobRefNumber, JobTitle, Location, JobDesc, Salary, Hours, Reports) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssss", $reference_number, $job_title, $location, $job_desc, $salary, $hours, $reports);

    //processing
    if (mysqli_stmt_execute($stmt)) {
    
        // Insert the requirements
        $req_stmt = mysqli_prepare($conn, "INSERT INTO requirements(Req, JobRefNumber) VALUES (?)");
        
        foreach ($requirements as $req_value) {
            $req_value = sanitize_input($req_value);
            mysqli_stmt_bind_param($req_stmt, "s,s", $req_value, $reference_number);
            mysqli_stmt_execute($req_stmt);
        }

        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Job Added</title>
                <style>
                    body {font-family: 'Geist Mono', sans-serif; background: #f8f8f8; }
                    .success-container { max-width: 500px; margin: 50px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; padding: 30px; text-align: center; }
                    h2 { color: #007700; }
                    .ref-number { font-size: 1.2em; font-weight: bold; color: #333; }
                    a { color: #0077cc; }
                </style>
            </head>
            <body>
                <div class='success-container'>
                    <h2>New Job Added Successfully!</h2>
                    <p>Your EOI number is:</p>
                    <div class='ref-number'>{$reference_number}</div>
                    <p>Updated to the career page.</p>
                    <p><a href='index.php'>Return to Home Page</a></p>
                </div>
            </body>
            </html>";
    } else {
        // Display error message if database insertion fails
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Submission Error</title>
            </head>
            <body>
                <h2>Error: " . htmlspecialchars(mysqli_error($conn)) . "</h2>
                <p><a href='addjob.php'>Try again</a></p>
            </body>
            </html>";
    }
?>