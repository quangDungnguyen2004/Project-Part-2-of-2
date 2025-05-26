<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<?php
    include "header.inc";
?>

<body>
    <?php
    include "nav.inc";
    ?>
    <main class="main">
        <div class="top-part">
            <div class="product-purpose2">
                <h1>About.</h1>
            </div>
            <img id="top-image" src="images/pinktree.png" alt="tree image">
            <div class="product-purpose3">
                <h1>Invent what's next—responsibly.</h1>
                <p class="after-purpose-text1">We're building AI that elevates people and society, and we're looking for
                    creative, mission-driven individuals to help lead the way.</p>
                <p class="after-purpose-text2">Values: These values reflect what we stand for. They guide our decisions,
                    shape our culture, and help us build AI with purpose.</p>
                <p class="after-purpose-text3">Bold, Responsible Innovation. We move fas...</p>
                <div class="see-more">See more</div>
            </div>
        </div>
        <div>
            <div class="mid-part">
                <div class="title-wrapper">
                    <h1>Our Team</h1>
                </div>
                <div class="our-team-top-wrapper">
                    <figure>
                        <img class="rounded-image" src="images/groupimage.png" alt="Group Photo" width="300">
                    </figure>
                    <p>Greeting. We are Earthling. Below are information regarding our group</p>
                </div>
                <table class="general-info-table">
                    <tr>
                        <th>Group Information</th>
                        <th>Student IDs</th>
                    </tr>
                    <tr>
                        <td>
                            <ul>
                                <li>Group Name: Earthlings</li>
                                <li>Class Time: 14:30 - 16:30</li>
                                <li>Day: Friday</li>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <li>Hien Long Dang: 105730438</li>
                                <li>Quoc Tri Nguyen: 105544639</li>
                                <li>Quang Dung Nguyen: 103810008</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">Members Contributions</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul>
                                <li>Hien Long Dang: 1. Use PHP to reuse common elements, 2. Create a file to store your database connection variables “settings.php”, 6. Update about page (about.php), 8: Enhancements, manage GitHub</li>
                                <li>Quoc Tri Nguyen: 4. Adding validated records to the EOI table, 5. HR manager queries (manage.php), 8: Enhancements,  manage Github</li>
                                <li>Quang Dung Nguyen: 3. Create an EOI table, 7. Jobs Description, 8: Enhancements,  manage Github</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="about-last-part">
            <table class="interests-table">
                <caption>
                    <h2><strong>Group Member Interests</strong></h2>
                </caption>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Interests</th>
                        <th>Programming Skills</th>
                        <th>From</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hien Long Dang</td>
                        <td>Video games, work</td>
                        <td>Barely enough</td>
                        <td>Vietnam</td>
                    </tr>
                    <tr>
                        <td>Quoc Tri Nguyen</td>
                        <td>Listen to music, going out, work</td>
                        <td>Barely enough</td>
                        <td>Vietnam</td>
                    </tr>
                    <tr>
                        <td>Quang Dung Nguyen</td>
                        <td>Listen to music, reading books</td>
                        <td>Barely enough</td>
                        <td>Vietnam</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <?php
    include "footer.inc";
    ?>
</body>

</html>
<!--
AI prompt used
- Give me the purpose and value of my company
-->