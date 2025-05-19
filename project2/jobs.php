<?php
    session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once("settings.php");

    $queryJob = "SELECT * FROM jobs";

    $jobs = mysqli_query($conn, $queryJob);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono&display=swap" rel="stylesheet">
    <title>Job Page</title>
</head>
<body>
    <?php
        include "nav.inc";
    ?>
    <main class="main">
        <div class="mid-part">
            <section>
                <div class="title-wrapper">
                    <h1>Featured roles</h1>
                </div>
                <form action="process_eoi.php" method="post">
                    <button class="apply-button" type="submit">Apply now ➜</button>
                </form>
            </section>
            <section>
                <div class="job-grid">
                    <?php
                    if ($jobs->num_rows > 0) {
                        while($row = $jobs->fetch_assoc()){
                            $refNum = $row['JobRefNumber'];

                            $req = "SELECT requirements.Req
                                FROM ((requirements
                                INNER JOIN jobrequirement ON jobrequirement.ReqID = requirements.ReqID)
                                INNER JOIN jobs ON jobs.JobRefNumber = jobrequirement.JobRefNumber)
                                WHERE jobs.JobRefNumber = '$refNum';
                            ";

                            $requirements = mysqli_query($conn, $req);

                            echo "<div class='job-box'>
                            <h3>". $row['JobTitle']."</h3>
                            <p class='interest-title'>📍".$row['Location']."<br><br>".$row['JobDesc']."</p>
                            
                            <p class='opportunity-details'><strong>Opportunity details</strong></p>
                            <p><strong>Salary Range:</strong> ".$row['Salary']." per annum</p>
                            <p><strong>Expected Hours:</strong> ".$row['Hours']."</p>
                            <p>
                                <strong>Key Requirements:</strong>
                            </p>
                            <ol>";
                            if ($requirements->num_rows > 0){
                                while($reqRow = $requirements->fetch_assoc()){
                                    echo "<li>".$reqRow['Req']."</li>";
                                };
                            };
                            echo "</ol>
                            <p><strong>Reports To:</strong>.".$row['Reports'].".</p>
                            <p><strong>Reference ID:</strong> ".$row['JobRefNumber']."</p>
                            </div>
                            ";
                        }

                    }
                    
                    ?>
                </div>
            </section>
            <section class="benefits-section">
                <div class="title-wrapper">
                    <h1>Benefits</h1>
                </div>
                <ul>
                    <li>Competitive salary packages</li>
                    <li>Flexible working hours and remote work options</li>
                    <li class="benefits-text0">Professional development and training opportunities</li>
                    <li class="benefits-text1">Collaborative and inclusive work environment</li>
                    <li class="benefits-text2">Opportunities for career growth and advancement</li>
                    <li class="benefits-text3">Generous paid time off and holiday policies</li>
                    <li class="benefits-text4">Employee discounts and perks</li>
                </ul>
                <div class="see-more">See more</div>
            </section>
        </div>
    </main>
    <div class="update-section">
        <aside>Latest update: 4/14/2025</aside>
    </div>
    <?php
        include "footer.inc";
    ?>
</body>
</html>
<!--
AI prompt used
- Give me ideas on job description of AI/ML Engineer in which my company is related to AI development similar to open AI
- Based on my code please adjust the content for Data Engineer, Frontend Dev, BA
- Give me benefits in working for the business
-->