<?php
require 'tasks.php';
//! ensuring only logged-in users can access this page, otherwise redirected to index.php with login/sign-up
if(!isset($_SESSION['user'])){
    header('location: index.php');
    exit();
}
//! getting/setting csrf token
if(!isset($_SESSION['csrf'])){
    $_SESSION['csrf'] = bin2hex(random_bytes(24));
}
$csrf = $_SESSION['csrf'];
//! ACTION on the logout button
if(isset($_GET['logout'])){
    logoutUser($csrf);
}
//! ACTION on the add card form
if(isset($_POST['submit-card'], $_POST['front-input-add-card'], $_POST['back-input-add-card'])){
    addCard($_POST['front-input-add-card'], $_POST['back-input-add-card'], $_POST['csrf-token']);
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
    <script src="home-script.js"></script>
    <title>STUDIOUS | Home</title>
</head>
<body>
<header class="header-with-nav">
    <!-- pop up displaying username of user signed in to the session -->
    <p id="user-pop-up">Signed in as <?php echo $_SESSION['user'];?></p>
    <!-- left part of the navigation bar with linked logo and name of the application -->
    <div id="logo-group">
        <a href="#"><img src="logo-icon.png" alt="logo" id="logo-icon"></a>
        <p id="logo-text">Studious</p>
    </div>
    <!-- middle part of the navigation bar with links to main features of the application -->
    <nav>
        <ul id="logged-nav-list">
            <li><a href="cards.php">Cards</a></li>
            <li class="current"><a href="home.php">Home</a></li>
            <li><a href="study.php">Study</a> </li>
        </ul>
    </nav>
    <!-- right part of the navigation bar with buttons to log out, as well as to switch light/dark mode and display username -->
    <div id="log-out-group">
        <div id="dark-mode-button" class="log-out-group-button"><span class="material-symbols-outlined" id="mode-icon">dark_mode</span></div>
        <div id="user-button" class="log-out-group-button"><span class="material-symbols-outlined">person</span></div>
        <a href="?logout"><div id="log-out-button" class="log-out-group-button"><span class="material-symbols-outlined">logout</span></div></a>
    </div>
</header>
<main class="logged-main">
    <div class="boxes">
        <!-- card with current date and time -->
        <div id="box1" class="box">
            <p class="box-heading">Happy Learning</p>
            <div class="box-main">
                <p id="time"></p>
                <p id="day"></p>
                <p id="date"></p>
            </div><a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">
            <div id="box-button1" class="box-button">
                <span class="material-symbols-outlined">
                arrow_forward
                </span>
                <span class="home-button-description">Vibe to some music</span>
            </div></a>
        </div>
        <!-- card displaying the number of study cards left to review today -->
        <div id="box2" class="box">
            <p class="box-heading">Plan for Today</p>
            <div class="box-main">
                <p><?php
                    $number = strval(getNumberOfTodayCards());
                    echo "$number"?></p>
                <p>cards</p>
                <p>left to review today</p>
            </div><a href="study.php">
            <div id="box-button2" class="box-button">
                <span class="material-symbols-outlined">
                arrow_forward
                </span>
                <span class="home-button-description">Get to studying</span>
            </div></a>
        </div>
        <!-- card with a form to create a new study card -->
        <form id="box3" class="box" method="post">
            <p class="box-heading">Add new cards</p>
            <label for="home-front-input-add-card">Front of the card (question): *
                <input autocomplete="off" type="text" id="home-front-input-add-card" name="front-input-add-card" pattern="(.|\s)*\S(.|\s)*" maxlength="320" required value="<?php echo @$_POST['front'] ?>">
            </label>
            <label for="home-back-input-add-card">Back of the card (answer): *
                <input autocomplete="off" type="text" id="home-back-input-add-card" name="back-input-add-card" pattern="(.|\s)*\S(.|\s)*" maxlength="320" required value="<?php echo @$_POST['back'] ?>">
            </label>
            <button type="submit" name="submit-card" id="box-button3" class="box-button">
                <span class="material-symbols-outlined">
                arrow_forward
                </span>
                    <span class="home-button-description">Create this card</span>
            </button>
            <input type="hidden" name="csrf-token" value="<?php echo $csrf;?>">
        </form>
        <!-- card displaying the total number of cards a user has in its database -->
        <div id="box4" class="box">
            <p class="box-heading">Your Knowledge Base</p>
            <div class="box-main">
                <p><?php
                    $number = strval(getNumberOfCards());
                    echo "$number"?></p>
                <p>cards</p>
                <p>in your database</p>
            </div><a href="cards.php">
            <div id="box-button4" class="box-button">
                <span class="material-symbols-outlined">
                arrow_forward
                </span>
                <span class="home-button-description">View and edit them</span>
            </div></a>
        </div>
    </div>
</main>
<script> </script>
</body>
</html>