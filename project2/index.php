<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono&display=swap" rel="stylesheet">
    <title>Home Page</title>
</head>
<body>
    <?php
        include "nav.inc";
    ?>
    <main class="main">
        <div class="top-part">
            <img id="top-image" src="../images/topportion.png" alt="sky image">
            <div class="product-purpose">
                <h1>The AI Tool To Expand Your Creativity</h1>
                <p>Design to improve you productivity</p>
            </div>
        </div>
        <div class="overview">
            <div>
                <p class="image-title">It's a <span id="partner">partner</span> who always stands by your side on creative projects, from initial brainstorming to final editing.</p>
                <img class="overview-images" src="../images/storyprompt.png" alt="Story Prompt">
            </div>
            <div>
                <p class="image-title">A <span id="tutor">tutor</span> who helps you turn your own ideas into working code.</p>
                <img class="overview-images" src="../images/codeprompt.png" alt="Code Prompt">
            </div>
            <div>
                <p class="image-title">Whether you have a simple idea or a detailed description, it can create an image to match your needs.</p>
                <img class="overview-images" src="../images/imageprompt.png" alt="Image Prompt">
            </div>
            <div class="see-more">Explore more features of Earthling AI</div>
        </div>
        <div class="mid-part">
            <div class="research">
                <h2>Latest research</h2>
            </div>
            <div class="news">
                <div class="left-news">
                    <img id="research-left" src="../images/bluepallete.png" alt="Left News Image">
                    <div class="image-label">Introducing the XXXXX benchmark</div>
                    <div class="image-date">Jan 24, 2025</div>
                </div>
                <div class="right-news">
                    <img id="research-right" src="../images/flower.png" alt="Right News Image">
                    <div class="image-label">Introducing the YYYYY model</div>
                    <div class="image-date">Mar 11, 2025</div>
                </div>
            </div>
            <div class="see-more">See more</div>
        </div>
        <div class="bottom-part">
            <h1 class="getting-started">Getting Started</h1>
            <div class="download-button">DOWNLOAD</div>
        </div>
    </main>
    <?php
        include "footer.inc";
    ?>
</body>
</html>
<!--
AI prompt used
- Generate blue palette art and red flower for me
*Additional note:I have used minor prompts with different AIs like GPT-4o, Claude 3.5 / 3.7 Sonnet to 
mostly help me fix my coding problems related to inappropriate formats as well as noticing unnecessary code. 
The rest of the designing work which is around 70% of the design are made through one own knowledge in Figma
-->