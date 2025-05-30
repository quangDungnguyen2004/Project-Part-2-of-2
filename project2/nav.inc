<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="nav">
  <div class="menu">
    <a href="index.php" class="clickable-wrapper">
      <div id="earthling-icon"></div>
      <span class="text-wrapper">EARTHLING</span>
    </a>
  </div>
  <div class="mid-selections">
    <a href="about.php" class="about-us">ABOUT US</a>
    <a href="#" class="features">FEATURES</a>
    <a href="#" class="enterprise">ENTERPRISE</a>
    <a href="jobs.php" class="careers">CAREERS</a>
    <a href="mailto:info@earthlingai.com.au" class="contact-us">CONTACT US</a>
  </div>
  <div class="hidden-menu">
    <img src="images/dropdownbutton.png" alt="drop down button" />
    <div class="dropdown-content">
      <ul>
        <li><a href="about.php" class="about-us">ABOUT US</a></li>
        <li><a href="#" class="features">FEATURES</a></li>
        <li><a href="#" class="enterprise">ENTERPRISE</a></li>
        <li><a href="jobs.php" class="careers">CAREERS</a></li>
        <li>
          <a href="mailto:info@earthlingai.com.au" class="contact-us"
            >CONTACT US</a
          >
        </li>
      </ul>
    </div>
  </div>
  <div class="left-selections">
    <?php if (isset($_SESSION['username'])): ?>
    <div class="login-menu">
      <img src="images/user.png" alt="drop down login" />
      <div class="dropdown-content">
        <ul>
          <li><a href="manage.php" class="login-button">MANAGE</a></li>
          <li><a href="logout.php" class="login-button">LOG OUT</a></li>
        </ul>
      </div>
    </div>
    <?php else: ?>
    <a href="login.php" class="login-button">LOG IN</a>
    <?php endif; ?>
    <div class="download-button">DOWNLOAD</div>
  </div>
</nav>
