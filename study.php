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

$today = date('Y-m-d');
//! ACTION on the positive feedback form
if (isset($_POST['submit-knew'])) {
    $cardId = $_POST['card-id'];
    $cardLevel = $_POST['card-level'];
    $card_level = $cardLevel + 1;
    $days_to_wait = $card_level * $card_level;
    $next_review = date('Y-m-d', strtotime($today . ' + ' . $days_to_wait . ' days'));
    feedbackCard($cardId, $card_level, $next_review, $_POST['csrf-token']);
}
//! ACTION on the negative feedback form
if (isset($_POST['submit-forgot'])) {
    $cardId = $_POST['card-id'];
    $card_level = 0;
    $days_to_wait = $card_level * $card_level;
    $next_review = date('Y-m-d', strtotime($today . ' + ' . $days_to_wait . ' days'));
    feedbackCard($cardId, $card_level, $next_review, $_POST['csrf-token']);
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
    <script src="study-script.js"></script>
    <title>STUDIOUS | Study</title>
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
            <li><a href="home.php">Home</a></li>
            <li class="current"><a href="study.php">Study</a> </li>
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

    <div class="no-data-div-study">
        <p>No more cards to study.</p>
        <p>Come again tomorrow.</p>
    </div>
    <?php
    $cards = getTodayOne();
    if (count($cards) != 0){
        $card = $cards[0];
        $card_id = $card[0];
        $card_front = $card[1];
        $card_back = $card[2];
        $card_level = $card[3];
        echo '
        <div class="cards">
            <!-- interactive card -->
            <div class="card-box box test-box">
                <p class="question">' . $card_front . '</p>
                <p id="test-answer" class="answer">' . $card_back . '</p>
                <p id="how-was-it">Rate your performance:</p>
                <!-- memory retention feedback controls -->
                <div class="upper-elements">
                    <form id="positive-feedback" method="post"><button type="submit" name="submit-knew" class="card-button edit-button">
                        <span class="material-symbols-outlined" id="card1-edit-button">thumb_up</span><span class="feedback-description"> KNEW IT</span>
                        <input type="hidden" name="csrf-token" value="'.$csrf.'">
                    </button>
                    <input type="hidden" name="card-level" value="'.$card_level.'">
                    <input type="hidden" name="card-id" value="'.$card_id.'">
                    </form>
                    <form id="negative-feedback" method="post"><button type="submit" name="submit-forgot" class="card-button delete_button" id="card1-delete-button">
                        <span class="feedback-description">FORGOT IT</span><span class="material-symbols-outlined">thumb_down</span>
                        <input type="hidden" name="csrf-token" value="'.$csrf.'">
                    </button>
                    <input type="hidden" name="card-level" value="'.$card_level.'">
                    <input type="hidden" name="card-id" value="'.$card_id.'">
                    </form>
                    <button id="reveal-button">Reveal the answer</button>
                </div>
            </div>
        </div>';
    }
    ?>
</main>
<script> </script>
</body>
</html>