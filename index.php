<?php
require "tasks.php";
//! ACTION on the submit sign-up button
if (isset($_POST['submit-sign-up'], $_POST['first-name-input-sign-up'], $_POST['last-name-input-sign-up'], $_POST['username-input-sign-up'], $_POST['email-input-sign-up'], $_POST['password-input-sign-up'], $_POST['gdpr-input-sign-up'])){
    $result = registerUser($_POST['first-name-input-sign-up'], $_POST['last-name-input-sign-up'], $_POST['username-input-sign-up'], $_POST['email-input-sign-up'], $_POST['password-input-sign-up'], $_POST['gdpr-input-sign-up']);
    if ($result == "Your sign up was successful. Redirecting you to the secure part of the app."){
        $login_result = loginUser($_POST['username-input-sign-up'], $_POST['password-input-sign-up']);
        echo "<script>alert(\"$login_result\")</script>";
    } else {
        echo "<script>alert(\"$result\")</script>";
    }
}

//! ACTION on the submit sign-in button
if (isset($_POST['submit-sign-in'], $_POST['username-input-sign-in'], $_POST['password-input-sign-in'])){
    $result = loginUser($_POST['username-input-sign-in'], $_POST['password-input-sign-in']);
    echo "<script>alert('$result');</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="icon" type="image/x-icon" href="logo-icon.png">
    <script src="script.js"></script>
    <script src="index-script.js"></script>
    <title>STUDIOUS</title>
</head>
<body>
<!-- sign in form as non-visible pup up -->

<form id="sign-in-form" class="pop-up-form" action="" method="post">
    <label for="username-input-sign-in">Username</label>
    <input type="text" id="username-input-sign-in" name="username-input-sign-in" placeholder="Enter your username ..." maxlength="32" pattern="^[a-zA-Z0-9]+$" required value="<?php echo @$_POST['username-input-sign-in'] ?>">
    <label for="password-input-sign-in">Password</label>
    <input type="password" id="password-input-sign-in" name="password-input-sign-in" placeholder="Enter your password ..." maxlength="32" required>
    <input type="submit" name="submit-sign-in" id="submit-input-sign-in" value="Sign In">
    <input type="button" id="cancel-input-sign-in" value="Cancel">
</form>
<header>
    <!-- pop up displaying username of user signed in to the session -->
    <p id="user-pop-up">You have to sign in first ;)</p>
    <!-- left part of the navigation bar with linked logo and name of the application -->
    <div id="logo-group">
        <a href="#"><img src="logo-icon.png" alt="logo" id="logo-icon"></a>
        <p id="logo-text">Studious</p>
    </div>
    <!-- right part of the navigation bar with buttons to sign in, as well as to switch light/dark mode and display username -->
    <!-- "log-out" IDs are maintained as the same visual styles apply here -->
    <div id="log-out-group">
        <div id="dark-mode-button" class="log-out-group-button"><span class="material-symbols-outlined" id="mode-icon">dark_mode</span></div>
        <div id="user-button" class="log-out-group-button"><span class="material-symbols-outlined">person</span></div>
        <div id="sign-in-button" class="log-out-group-button"><span class="material-symbols-outlined">login</span></div>
    </div>
</header>
<main>
    <!-- part filling the entire screen with name, description and buttons, which direct users to the right form -->
    <div id="intro-div">
        <h1 class="animate-character">
            Studious
        </h1>
        <p>Revolution in learning.<br>Reimagined.</p>
        <div id="intro-buttons">
            <a href="#sign-up-form"><div id="first-time-here">First time here?</div></a>
            <div id="already-a-member">Already a member?</div>
        </div>
    </div>
    <!-- part with sign up form -->
    <div id="intro-div-two">
        <form id="sign-up-form" action="" method="post">
            <p class="required-field">Fields denoted with an asterisk (*) are required</p>
            <label for="first-name-input-sign-up">First Name <span class="required-field">*</span></label>
            <ul id="first-name-requirements" >
                <li>Length from 1 to 32</li>
                <li>No spaces allowed</li>
            </ul>
            <input autocomplete="off" type="text" id="first-name-input-sign-up" name="first-name-input-sign-up" maxlength="32" pattern="^[a-zA-Zá-žÁ-Ž]+$" required value="<?php if(isset($_POST['first-name-input-sign-up'])){echo htmlspecialchars($_POST['first-name-input-sign-up']);} ?>">
            <label for="last-name-input-sign-up">Last Name <span class="required-field">*</span></label>
            <ul id="last-name-requirements" >
                <li>Length from 1 to 32</li>
                <li>No spaces allowed</li>
            </ul>
            <input autocomplete="off" type="text" id="last-name-input-sign-up" name="last-name-input-sign-up" maxlength="32" pattern="^[a-zA-Zá-žÁ-Ž]+$" required value="<?php if(isset($_POST['last-name-input-sign-up'])){echo htmlspecialchars($_POST['last-name-input-sign-up']);} ?>">
            <label for="username-input-sign-up">Username <span class="required-field">*</span></label>
            <ul id="username-requirements" >
                <li>Only a-z, A-Z, 0-9 allowed</li>
                <li>Length from 1 to 32</li>
                <li>No spaces allowed</li>
                <li>Unique and available</li>
            </ul>
            <input autocomplete="off" type="text" id="username-input-sign-up" name="username-input-sign-up" maxlength="32" pattern="^[a-zA-Z0-9]+$" required  value="<?php if(isset($_POST['username-input-sign-up'])){echo htmlspecialchars($_POST['username-input-sign-up']);} ?>">
            <label for="email-input-sign-up">Email <span class="required-field">*</span></label>
            <ul id="email-requirements" >
                <li>Correct email format</li>
                <li>Length from 6 to 320</li>
                <li>No spaces allowed</li>
                <li>Not already registered</li>
            </ul>
            <input autocomplete="off" type="email" id="email-input-sign-up" name="email-input-sign-up" maxlength="320" pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-z]{2,4}$" required  value="<?php if(isset($_POST['email-input-sign-up'])){echo htmlspecialchars($_POST['email-input-sign-up']);} ?>">
            <label for="password-input-sign-up">Password <span class="required-field">*</span></label>
            <ul id="password-requirements" >
                <li>Lowercase letter (a-z)</li>
                <li>Uppercase letter (A-Z)</li>
                <li>Digit (0-9)</li>
                <li>Length from 8 to 32</li>
                <li>No leading or trailing spaces</li>
            </ul>
            <input type="password" id="password-input-sign-up" name="password-input-sign-up" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,32}$" maxlength="32" required>
            <input type="checkbox" id="gdpr-input-sign-up" name="gdpr-input-sign-up" required>
            <label for="gdpr-input-sign-up">By signing up to our service you agree with our Terms & Conditions <span class="required-field">*</span></label>
            <input type="submit" name="submit-sign-up" value="Sign Up">
            <p class="server-output"><?php echo @$result;?></p>
        </form>
    </div>
</main>
<script> </script>
</body>
</html>