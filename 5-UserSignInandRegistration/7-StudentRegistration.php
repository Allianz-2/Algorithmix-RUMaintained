<?php
    include '10-RegistrationPHP.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - RUMaintained</title>
    <link rel="stylesheet" href="../5-UserSignInandRegistration\5-CSS\11-Registration.css">
    <script src="12-RegistrationJavacript.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="wallpaper">
               <img src="../Images/General/rhodes image.jpeg" alt="wallpaper">
            </div>
        </div>
        <div class="right-panel">
            <h1>Student Registration</h1>
            <form action="7-StudentRegistration.php" method="POST" id="registration-form" data-role="student">
            <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="userID">Student Number</label>
                    <input type="text" id="userID" name="userID" pattern="^G[0-9]{2}[A-Z]{1}[0-9]{4}$" placeholder="G12A3456" required>                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email_address" name="email_address" placeholder="Please use your university email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" pattern=".{8,}" title="Password must be at least 8 characters long" required>                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>

                <div class="form-group">
                    <label for="address">Select Residence:</label>
                    <select name="residenceID" id="residence" required>
                      <option value="Select Residence"></option>
                      <optgroup label="Allen Webb Hall">
                        <option value="AWCN">Canterbury House</option>
                        <option value="AWCA">Canterbury Annex House</option>
                        <option value="AWSL">Salisbury House</option>
                        <option value="AWTR">Truro House</option>
                        <option value="AWWC">Winchester House</option>
                      </optgroup>
                      <optgroup label="Courtenay-Latimer Hall">
                        <option value="CHBE">Beit House</option>
                        <option value="CHJM">Jameson House</option>
                        <option value="CHOR">Oriel House</option>
                      </optgroup>
                      <optgroup label="Desmond Tutu Hall">
                        <option value="DTEK">Ellen Kuzwayo House</option>
                        <option value="DTAC">Amina Cachalia House</option>
                        <option value="HMCT">Calata House</option>
                        <option value="DTMS">Margaret Smith House</option>
                        <option value="HMH7">Hilltop 7 House</option>
                        <option value="HMH8">Hilltop 8 House</option>
                      </optgroup>
                      <optgroup label="Drostdy Hall">
                        <option value="DHAG">Allan Gray House</option>
                        <option value="DHCE">Celeste House</option>
                        <option value="DHGH">Graham House</option>
                        <option value="DHPA">Prince Alfred House</option>
                      </optgroup>
                      <optgroup label="Founders Hall">
                        <option value="FHBT">Botha House</option>
                        <option value="FHCL">College House</option>
                        <option value="FHCR">Cory House</option>
                        <option value="FHM">Matthews House</option>
                      </optgroup>
                      <optgroup label="Hobson Hall">
                        <option value="HHDH">Dingemans House</option>
                        <option value="HHHH">Hobson House</option>
                        <option value="HHLH">Livingstone House</option>
                        <option value="HHMH">Milner House</option>
                      </optgroup>
                      <optgroup label="Jan Smuts Hall">
                        <option value="JSAD">Adamson House</option>
                        <option value="JSAS">Atherstone House</option>
                        <option value="JSRS">Jan Smuts House</option>
                        <option value="JSNW">New House</option>
                      </optgroup>
                      <optgroup label="Miriam Makeba Hall">
                        <option value="MMCH">Chris Hani House</option>
                        <option value="MMPR">Piet Retief House</option>
                        <option value="MMTP">Thomas Pringle House</option>
                        <option value="MMWH">Walker House</option>
                      </optgroup>
                      <optgroup label="Kimberley Hall">
                        <option value="KHCB">Cullen Bowles House</option>
                        <option value="KHDB">De Beers House</option>
                        <option value="KHRP">Rosa Parks House</option>
                        <option value="KHGF">Goldfields House</option>
                      </optgroup>
                      <optgroup label="Lilian Ngoyi Hall">
                        <option value="LNCY">Centenary House</option>
                        <option value="LNRF">Ruth First House</option>
                        <option value="LNJS">Joe Slovo House</option>
                        <option value="LNVM">Victoria Mxenge House</option>
                      </optgroup>
                      <optgroup label="Nelson Mandela Hall">
                        <option value="NMSK">Stanley Kidd House</option>
                        <option value="NMAT">Adelaide Tambo House</option>
                        <option value="NMGB">Guy Butler House</option>
                        <option value="NMHJ">Helen Joseph House</option>
                      </optgroup>
                      <optgroup label="St Mary Hall">
                        <option value="SMJK">John Kotze House</option>
                        <option value="SMLB">Lilian Britten House</option>
                        <option value="SMOS">Olive Schreiner House</option>
                        <option value="SMPH">Phelps House</option>
                      </optgroup>
                    </select>
                  </div>
                
                <input type="hidden" name="role" value="S">
                
                <div class="form-group">
                    <input type="checkbox" id="termsAndConditions" name="termsAndConditions" required>
                    <label for="termsAndConditions">
                        I accept the <a href="../3-UserRegistration/1-TermsPolicy.html" id="termsLink">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit" name="submit" class="btn">Register</button>
            </form>
            <div id="error-message" class="error-message"></div>
        </div>
    </div>
</body>
</html>