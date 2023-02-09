<?php
require 'tasks.php';
//! getting/setting sorting and filtering cookies
$sorting = (string)($_COOKIE['sorting'] ?? 'id-desc');
$filtering = (string)($_COOKIE['filtering'] ?? 'all-levels');
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
//! ACTION when the add card form is submitted
if(isset($_POST['submit-input-add-card'], $_POST['front-input-add-card'], $_POST['back-input-add-card'])){
    addCard($_POST['front-input-add-card'], $_POST['back-input-add-card'], $_POST['csrf-token']);
}
//! ACTION when the edit card form is submitted
if(isset($_POST['submit-input-edit-card'], $_POST['front-input-edit-card'], $_POST['back-input-edit-card'])){
    editCard($_POST['id-input-edit-card'], $_POST['front-input-edit-card'], $_POST['back-input-edit-card'], $_POST['csrf-token']);
}



//! ACTION when the sort/filter form is submitted
if(isset($_POST['submit-display-settings'])){
    setcookie('sorting', $_POST['sorting']);
    setcookie('filtering', $_POST['filtering']);
    header('location:cards.php?page=1');
}

//! calculate the pages from database for pagination
$total_cards = mysqli_num_rows(getCards($sorting, $filtering));
$cards_per_page = 6;
$num_of_pages = ceil($total_cards/$cards_per_page);
//! set the correct page in pagination
if(isset($_GET['page'])){
    $page = $_GET['page'];
    if ($page > $num_of_pages){
        $page = 1;
    }
} else {
    $page = 1;
}
$start_from = ($page-1)*$cards_per_page;
//! get just the right amount of cards for the current page complying with the sorting and filtering requirements
$result = getCardsForPage($start_from, $cards_per_page, $sorting, $filtering);

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
    <script src="cards-script.js"></script>
    <title>STUDIOUS | Cards</title>
</head>
<body>
<!-- form to add a new card as a non-visible pop-up -->
<form id="add-card-form" class="pop-up-form" method="post">
    <label for="front-input-add-card">Front of the card (question): *</label>
    <input autocomplete="off" type="text" id="front-input-add-card" name="front-input-add-card" pattern="(.|\s)*\S(.|\s)*" maxlength="280" required>
    <label for="back-input-add-card">Back of the card (answer): *</label>
    <input autocomplete="off" type="text" id="back-input-add-card" name="back-input-add-card" pattern="(.|\s)*\S(.|\s)*" maxlength="280" required>
    <input type="submit" name="submit-input-add-card" id="submit-input-add-card" value="Add card">
    <input type="hidden" name="csrf-token" value="<?php echo $csrf;?>">
    <input type="button" id="close-input-add-card" value="Close">
</form>
<!-- form to edit the current card -->
<form id="edit-card-form" class="pop-up-form" method="post">
    <label for="id-input-edit-card">Unique integer ID of the card: *</label>
    <input value="" autocomplete="off" type="number" min="0" step="1" id="id-input-edit-card" name="id-input-edit-card">
    <label for="front-input-edit-card">Front of the card (question): *</label>
    <input autocomplete="off" type="text" id="front-input-edit-card" name="front-input-edit-card" pattern="(.|\s)*\S(.|\s)*" maxlength="320" disabled>
    <label for="back-input-edit-card">Back of the card (answer): *</label>
    <input autocomplete="off" type="text" id="back-input-edit-card" name="back-input-edit-card" pattern="(.|\s)*\S(.|\s)*" maxlength="320" disabled>
    <input type="submit" name="submit-input-edit-card" id="submit-input-edit-card" value="Save edit" disabled>
    <input type="hidden" name="csrf-token" value="<?php echo $csrf;?>">
    <input type="button" id="close-input-edit-card" value="Close">
</form>
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
            <li class="current"><a href="cards.php">Cards</a></li>
            <li><a href="home.php">Home</a></li>
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
<!-- button that displays the add-card-form -->
<button id="plus-button">
    <span class="material-symbols-outlined">add</span>
</button>
<button id="edit-button">
    <span class="material-symbols-outlined">edit</span>
</button>
<!-- main part displaying all cards signed-in user has created -->
<main class="logged-main">
    <div class="cards-and-controls">
        <div class="controls">
            <form id="control-form" method="post">
                <div>
                    <label for="sorting">Sort by:
                    <select name="sorting" id="sorting">
                        <option value="id-desc" <?php if($sorting == 'id-desc') echo "selected"; ?>>Newest</option>
                        <option value="id-asc" <?php if($sorting == 'id-asc') echo "selected"; ?>>Oldest</option>
                        <option value="review-desc" <?php if($sorting == 'review-desc') echo "selected"; ?>>Farthest from review</option>
                        <option value="review-asc" <?php if($sorting == 'review-asc') echo "selected"; ?>>Closest to review</option>
                        <option value="front-desc" <?php if($sorting == 'front-desc') echo "selected"; ?>>Question/front (z-a)</option>
                        <option value="front-asc" <?php if($sorting == 'front-asc') echo "selected"; ?>>Question/front (a-z)</option>
                    </select></label>
                </div>
                <div>
                    <label for="filtering">Filter in only:
                    <select name="filtering" id="filtering">
                        <option value="all-levels" <?php if($filtering == 'all-levels') echo "selected"; ?>>Show all cards</option>
                        <option value="level-0" <?php if($filtering == 'level-0') echo "selected"; ?>>Show only cards of LEVEL 0</option>
                        <option value="level-1" <?php if($filtering == 'level-1') echo "selected"; ?>>Show only cards of LEVEL 1</option>
                        <option value="level-2" <?php if($filtering == 'level-2') echo "selected"; ?>>Show only cards of LEVEL 2</option>
                        <option value="level-3" <?php if($filtering == 'level-3') echo "selected"; ?>>Show only cards of LEVEL 3</option>
                        <option value="level-4" <?php if($filtering == 'level-4') echo "selected"; ?>>Show only cards of LEVEL 4</option>
                        <option value="level-5" <?php if($filtering == 'level-5') echo "selected"; ?>>Show only cards of LEVEL 5</option>
                        <option value="level-6" <?php if($filtering == 'level-6') echo "selected"; ?>>Show only cards of LEVEL 6</option>
                        <option value="level-7" <?php if($filtering == 'level-7') echo "selected"; ?>>Show only cards of LEVEL 7</option>
                        <option value="level-8-higher" <?php if($filtering == 'level-8-higher') echo "selected"; ?>>Show only cards of higher level</option>
                    </select></label>
                </div>
                <input type="submit" name="submit-display-settings" value="Apply and Refresh">
            </form>
        </div>
        <div class="no-data-div-cards" <?php if ($total_cards>0){echo 'style="visibility: hidden"';}?>>
            <p>There are no cards to display.</p>
            <p>Create some using the + button.</p>
            <p>Or alter the criteria above.</p>
        </div>
        <div class="cards">

            <?php
            if ($result){
                while($row=$result->fetch_assoc()){
                    $id = $row['id'];
                    $front = $row['front'];
                    $back = $row['back'];
                    $creation_date = $row['creationDate'];
                    $creation_date_original = DateTime::createFromFormat('Y-m-d', $creation_date);
                    $creation_date_display = $creation_date_original->format('d.m.Y');
                    $next_review_date = $row['nextReviewDate'];
                    $level = $row['level'];
                    $next_review_date_original = DateTime::createFromFormat('Y-m-d', $next_review_date);
                    $next_review_date_display = $next_review_date_original->format('d.m.Y');
                    echo '
                    <div class="card-box box">
                        <div class="upper-elements">
                            
                            
                            <div onclick="setTimeout(function (){}, 1)">
                                <button class="card-button id-button tooltip" disabled>
                                <span class="tooltip-text">ID: '.$id.' | LEVEL: '.$level.'</span>
                                    <span class="material-symbols-outlined unselectable" id="card1-edit-button">badge</span>
                                
                                </button>
                            </div>
                            <div>
                                <p class="next-review">Created on: '.$creation_date_display.'</p>
                                <p class="next-review">Next review: '.$next_review_date_display.'</p>
                            </div>
                            <a href="delete.php?id='.$id.'&csrfToken='.$csrf.'" class="card-button delete_button"><span class="material-symbols-outlined">delete</span></a>
                        </div>
                        <p class="question">'.$front.'</p>
                        <p class="answer">'.$back.'</p>
                    </div>
                ';
                }
            }
            ?>
    </div>
        <div class="pagination">
            <?php

            for($i=1;$i<=$num_of_pages;$i++){
                if ($i == $page){
                    echo '<a class="pagination-number current-page" href="cards.php?page='.$i.'">'.$i.'</a>';
                } else {
                    echo '<a class="pagination-number" href="cards.php?page='.$i.'">'.$i.'</a>';
                }
            }
            ?>
        </div>
    </div>
</main>
<script> </script>
</body>
</html>