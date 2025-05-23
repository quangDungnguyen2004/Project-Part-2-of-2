<?php include("header.inc"); ?>
<?php include("menu.inc"); ?>

<main class="main">
        <div class="form-wrapper">
            <form class="application-form" action="confirmation.php" method="post">
                <h2 class="form-title">Application Form</h2>
                <p class="under-form-title">Application form for Employee</p>
                <div class="input-block">
                    <label for="refnum">Reference Number</label>
                    <select class="select-box" name="Reference_number" id="refnum" required>
                        <option value="" selected disabled>Select Reference ID</option>
                        <option value="FT101">FT101</option>
                        <option value="FT102">FT102</option>
                        <option value="FT103">FT103</option>
                        <option value="FT104">FT104</option>
                        <option value="FT105">FT105</option>
                    </select>
                </div>
                <div class="input-row">
                    <div class="input-block">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="Full_name" maxlength="20" pattern="^[A-Za-z]{1,20}$" placeholder="Type here..." autocomplete="given-name" required title="First name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="Last_name" maxlength="20" pattern="^[A-Za-z]{1,20}$" placeholder="Type here..." autocomplete="family-name" required title="Last name must contain only letters (A-Z), max 20 characters.">
                    </div>
                    <div class="input-block">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="Date_of_birth" required>
                    </div>
                </div>
                <div class="input-block">
                    <fieldset>
                        <legend>Gender</legend>
                        <div class="gender-selection-wrapper">
                            <div>
                                <input type="radio" id="male" name="Gender" value="Male" checked required>
                                <label for="male">Male</label>
                            </div>
                            <div>
                                <input type="radio" id="female" name="Gender" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div>
                                <input type="radio" id="other" name="Gender" value="Other">
                                <label for="other">Others</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <fieldset>
                        <legend>Address Details</legend>
                        <div class="address-option">
                            <label for="street">Street Address</label>
                            <input type="text" id="street" name="Street" maxlength="40" placeholder="Enter your street address" required>
                        </div>
                        <div class="address-option">
                            <label for="suburb">Suburb/Town</label>
                            <input type="text" id="suburb" name="Suburb" maxlength="40" placeholder="Enter your suburb/town" required>
                        </div>
                        <div class="address-option">
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="Postcode" maxlength="4" pattern="020\d|02[1-9]\d|0[3-9]\d{2}|[1-8]\d{3}|9[0-8]\d{2}|99[0-3]\d|994[0-4]" placeholder="Enter your postcode" required title="Please enter a valid 4-digit Australian postcode between 0200 and 9944">
                        </div>
                        <div class="address-option">
                            <label for="state">State</label>
                            <select class="select-box" name="State" id="state" required>
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
                    <label for="email">Email</label>
                    <input type="email" id="email" name="Email" placeholder="Enter your email" autocomplete="email" required>
                </div>
                <div class="input-block">
                    <label for="phone">Phone Number</label>
                    <!--Pattern from 3Widgets.com Regex generator-->
                    <input type="tel" id="phone" name="Phone_number" maxlength="12" pattern="04[0-9]{2} [0-9]{3} [0-9]{3}" placeholder="0412 345 678" title="Format: 0412 345 678" autocomplete="tel" required>
                </div>
                <div class="input-block">
                    <fieldset class="skills-section">
                        <legend>Required Technical Skills</legend>
                        <div class="skill-option">
                            <input type="checkbox" id="req1" name="Tech_skills" value="req1" required>
                            <label for="req1">Requirement 1</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="req2" name="Tech_skills" value="req2">
                            <label for="req2">Requirement 2</label>
                        </div>
                        <div class="skill-option">
                            <input type="checkbox" id="req3" name="Tech_skills" value="req3">
                            <label for="req3">Requirement 3</label>
                        </div>
                    </fieldset>
                </div>
                <div class="input-block">
                    <label for="otherskills">Other Skills</label>
                    <textarea id="otherskills" name="Other_skills" rows="4" cols="40" placeholder="List any other skills"></textarea>
                </div>
                <div class="input-block">
                    <input class="left-button" type="reset" value="Reset">
                    <input class="right-button" type="submit" value="Apply"> 
                </div>
            </form>
        </div>
    </main>

    <?php include("footer.inc"); ?>