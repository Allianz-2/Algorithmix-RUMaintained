         
          <?php if (isset($_SESSION['userID'])): ?>
    <div class="profile-info">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle">
                <?php if (isset($_SESSION['ProfilePath']) && !empty($_SESSION['ProfilePath'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['ProfilePath']); ?>" alt="Profile Photo" class="profile-photo" style="width: 20px; height: 20px; border-radius: 50%;">
                <?php else: ?>
                    <i class="fas fa-user default-icon" id="default-icon"></i>
                <?php endif; ?>
            </a>
            <div class="dropdown-content">
                <a href="7-RedirectProfile.php">Profile</a>
                <a href="..\5-UserSignInandRegistration\15-Logout.php">Logout</a>
            </div>
        </div>
        <span class="profile-name">
            <?php echo 'Welcome ' . htmlspecialchars($_SESSION['Firstname']) ; ?>
        </span>
    </div>
<?php else: ?>
    <div class="auth-buttons">
        <a href="../5-UserSignInandRegistration/6-SignInPage.php" class="cta-button">Sign in</a>
        <a href="../5-UserSignInandRegistration/5-RegistrationStep1.html" class="cta-button login-button">Register</a>
    </div>
<?php endif; ?>
