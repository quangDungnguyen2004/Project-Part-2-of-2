<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    include "header.inc";
    ?>
    <main class="main">
        <div class="mid-part">
            <section>
                <div class="title-wrapper">
                    <h1>Featured roles</h1>
                </div>
                <form action="apply.php" method="post">
                    <button class="apply-button" type="submit">Apply now ➜</button>
                </form>
            </section>
            <section>
                <div class="job-grid">

                    <div class="job-box">
                        <h3>AI/ML Engineer (Foundational Models)</h3>
                        <p class="interest-title">📍Melbourne CBD / Hybrid<br><br>Join our core AI research team to
                            develop scalable, general-purpose models that power the next generation of intelligent
                            systems.</p>

                        <p class="opportunity-details"><strong>Opportunity details</strong></p>
                        <p><strong>Salary Range:</strong> $110,000 - $140,000 per annum</p>
                        <p><strong>Expected Hours:</strong> Full-time, 38 - 40 hours/week</p>
                        <p><strong>Key Requirements:</strong></p>
                        <ol>
                            <li>3+ years experience in ML model development, preferably with Transformers or Deep RL
                            </li>
                            <li>Strong background in Python, PyTorch, and large-scale data processing</li>
                            <li>Familiarity with distributed training, vector databases, or LLM fine-tuning</li>
                        </ol>
                        <p><strong>Bonus points:</strong></p>
                        <ul>
                            <li>Published research in ML conferences (e.g., NeurIPS, ICML)</li>
                            <li>Experience deploying ML models in production</li>
                        </ul>
                        <p><strong>Reports To:</strong> Director of Applied AI</p>
                        <p><strong>Reference ID:</strong> FT105</p>
                    </div>

                    <div class="job-box">
                        <h3>Data Engineer</h3>
                        <p class="interest-title">📍Sydney, NSW<br><br>We're looking for a Data Engineer to design,
                            build, and maintain scalable data pipelines and infrastructure for our AI systems.</p>

                        <p class="opportunity-details"><strong>Opportunity details</strong></p>
                        <p><strong>Salary Range:</strong> $95,000 - $115,000 per annum</p>
                        <p><strong>Expected Hours:</strong> Full-time, 38 - 40 hours/week</p>
                        <p><strong>Key Requirements:</strong></p>
                        <ol>
                            <li>Minimum 2-4 years experience in data engineering or backend development</li>
                            <li>Degree in Computer Science, Engineering, or a related field</li>
                            <li>Experience with tools like Apache Spark, Kafka, Airflow, and SQL/NoSQL databases</li>
                        </ol>
                        <p><strong>Bonus points:</strong></p>
                        <ul>
                            <li>Experience with cloud platforms (AWS, GCP, or Azure)</li>
                            <li>Knowledge of real-time data processing</li>
                        </ul>
                        <p><strong>Reports To:</strong> Head of Data Engineering</p>
                        <p><strong>Reference ID:</strong> FT102</p>
                    </div>

                    <div class="job-box">
                        <h3>Frontend Developer (AI Interfaces)</h3>
                        <p class="interest-title">📍Remote / Sydney HQ<br><br>We're looking for a creative frontend
                            developer to craft beautiful, intuitive UIs that bring cutting-edge AI capabilities to life
                            for users and enterprises.</p>

                        <p class="opportunity-details"><strong>Opportunity details</strong></p>
                        <p><strong>Salary Range:</strong> $90,000 - $115,000 per annum</p>
                        <p><strong>Expected Hours:</strong> Full-time, 37 - 40 hours/week</p>
                        <p><strong>Key Requirements:</strong></p>
                        <ol>
                            <li>2+ years experience in frontend development (React, TypeScript preferred)</li>
                            <li>Strong UX/UI sensibility and experience building dashboards or developer tools</li>
                            <li>Bonus: Experience integrating with AI APIs or WebSocket-based live apps</li>
                        </ol>
                        <p><strong>Bonus points:</strong></p>
                        <ul>
                            <li>Experience with animation or interaction libraries (e.g., Framer Motion)</li>
                            <li>Strong eye for responsive, accessible design</li>
                        </ul>
                        <p><strong>Reports To:</strong> Head of Product Engineering</p>
                        <p><strong>Reference ID:</strong> FT106</p>
                    </div>

                    <div class="job-box">
                        <h3>Business Analyst (AI Solutions)</h3>
                        <p class="interest-title">📍Melbourne or Hybrid<br><br>As a Business Analyst, you'll bridge the
                            gap between AI technical teams and business stakeholders, ensuring the delivery of impactful
                            data-driven solutions.</p>

                        <p class="opportunity-details"><strong>Opportunity details</strong></p>
                        <p><strong>Salary Range:</strong> $85,000 - $110,000 per annum</p>
                        <p><strong>Expected Hours:</strong> Full-time, 38 hours/week</p>
                        <p>
                            <strong>Key Requirements:</strong>
                        </p>
                        <ol>
                            <li>2+ years of experience as a BA, preferably in tech or data-driven environments</li>
                            <li>Strong analytical and communication skills</li>
                            <li>Understanding of AI/ML concepts is a plus</li>
                        </ol>
                        <p><strong>Bonus points:</strong></p>
                        <ul>
                            <li>Familiarity with Agile tools like JIRA or Trello</li>
                            <li>Experience working on AI-related projects</li>
                        </ul>
                        <p><strong>Reports To:</strong> Product Manager</p>
                        <p><strong>Reference ID:</strong> FT104</p>
                    </div>

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