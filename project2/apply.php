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
        <div class="form-wrapper">
            <form class="application-form" action="confirmation.php" method="post">
                <h2 class="form-title">Application Form</h2>
                <p class="under-form-title">Application form for Employee</p>
                <div class="input-block">
                    <!-- Job reference number -->
                    <label for="refnum">Reference Number</label>
                    <select class="select-box" name="reference_number" id="refnum" required>
                        <option value="" selected disabled>Select Reference ID</option>
                        <?php 
                            session_start();
        
                            error_reporting(E_ALL);
                            ini_set('display_errors', 1);
                            require_once("settings.php");
                            $queryJob = "SELECT JobRefNumber FROM jobs";

                            $jobs = mysqli_query($conn, $queryJob);
                            
                            if ($jobs->num_rows > 0) {
                                while($row = $jobs->fetch_assoc()){
                                    echo "<option value=".$row['JobRefNumber'].">".$row['JobRefNumber']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="input-row">
                    <div class="input-block">
                        <!-- First name -->
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="first_name" maxlength="20" pattern="^[A-Za-z]{1,20}$"
                            placeholder="Type here..." required
                            title="First name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <!-- Last name -->
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="last_name" maxlength="20" pattern="^[A-Za-z]{1,20}$"
                            placeholder="Type here..." required
                            title="Last name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <!-- Date of Birth -->
                        <label for="dob">Date of Birth</label>
                        <input type="text" id="dob" name="date_of_birth"
                            pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/\d{4}" placeholder="dd/mm/yyyy"
                            title="Format: dd/mm/yyyy (e.g., 31/12/1999)" required>
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
                            <input type="text" id="street" name="street" maxlength="40"
                                placeholder="Enter your street address" required>
                        </div>
                        <div class="address-option">
                            <!-- Suburb/Town -->
                            <label for="suburb">Suburb/Town</label>
                            <input type="text" id="suburb" name="suburb" maxlength="40"
                                placeholder="Enter your suburb/town" required>
                        </div>
                        <div class="address-option">
                            <!-- Postcode -->
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" maxlength="4"
                                pattern="020\d|02[1-9]\d|0[3-9]\d{2}|[1-8]\d{3}|9[0-8]\d{2}|99[0-3]\d|994[0-4]"
                                placeholder="Enter your postcode" required
                                title="Please enter a valid 4-digit Australian postcode between 0200 and 9944">
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
                    <input type="email" id="email" name="email" placeholder="example@domain.com"
                        pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                </div>
                <div class="input-block">
                    <!-- Phone number -->
                    <label for="phone">Phone Number</label>
                    <!--Pattern from 3Widgets.com Regex generator-->
                    <input type="tel" id="phone" name="phone_number" maxlength="12"
                        pattern="04[0-9]{2} [0-9]{3} [0-9]{3}" placeholder="0412 345 678" title="Format: 0412 345 678"
                        required>
                </div>
                <div class="input-block">
                    <fieldset class="skills-section" required>
                        <!-- Technical Skills -->
                        <legend>Required Technical Skills</legend>
                        <div class="skill-option">
                            <input type="checkbox" id="Python" name="tech_skills[]" value="Python">
                            <label for="Python">Python</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="R" name="tech_skills[]" value="R">
                            <label for="R">R</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="Julia" name="tech_skills[]" value="Julia">
                            <label for="Julia">Julia</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="Java" name="tech_skills[]" value="Java">
                            <label for="Java">Java</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="C++" name="tech_skills[]" value="C++">
                            <label for="C++">C++</label>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <!-- Other skills -->
                    <label for="other_skills">Other Skills</label>
                    <textarea id="other_skills" name="other_skills" rows="4" cols="40"
                        placeholder="List any other skills"></textarea>
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