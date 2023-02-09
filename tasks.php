<?php
require "config.php";

/**
 * Function to establish a connection with our MySQL database
 * @return false|mysqli
 */
function connect(){
    $mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);
    if ($mysqli -> connect_errno != 0) {
        //! it would be a security risk to display database error messages to the user
        //! thus we create this error message
        $error = $mysqli -> connect_error;
        $error_date = date("F j, Y, g:i a");
        $error_message = "$error | $error_date \r\n";
        //! and store it to database-logs.txt file
        file_put_contents("database-logs.txt", $error_message, FILE_APPEND);
        return false;
    } else {
        //! in case database connection is successful, we return the MySQL object
        return $mysqli;
    }
}

/**
 * Function to sign up a user with thorough validation and prevention of double-submission
 * @param $first_name
 * @param $last_name
 * @param $username
 * @param $email
 * @param $password
 * @param $consent_given
 * @return string
 */
function registerUser($first_name, $last_name, $username, $email, $password, $consent_given): string
{
    $first_name = htmlspecialchars($first_name);
    $last_name = htmlspecialchars($last_name);
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $mysqli = connect();
    $arguments = func_get_args();
    //! check for users database and if not exists create one
    $stmt = $mysqli->prepare("create table if not exists users (id int not null auto_increment, firstName varchar(32) not null, lastName varchar(32) not null, username varchar(32) not null unique, email varchar(320) not null unique, password varchar(320) not null, consentGiven boolean not null, primary key (id));");
    $stmt->execute();
    //! validation of data from the register form
    //! strip user input of any whitespace
    $arguments = array_map(function ($value) {
        return trim(htmlspecialchars($value));
    }, $arguments);
    //! all fields are required, return error if an argument is empty
    foreach ($arguments as $argument) {
        if (empty($argument)) {
            return "All fields are required";
        }
    }
    //! prevention against opening and closing tags as potentially malicious code
    foreach ($arguments as $argument) {
        if (preg_match('/([<|>])/', $argument)) {
            return "<> characters are not allowed";
        }
    }
    //! validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Email is not valid";
    }
    //! checking that the email is unique (user is not yet registered)
    $stmt = $mysqli -> prepare("select email from users where email = ?");
    $stmt -> bind_param("s", $email);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $data = $result -> fetch_assoc();
    if ($data != NULL) {
        return "ERROR: User with that email found";
    }
    //! checking that the arguments have the proper length
    if (strlen($first_name) > 32){
        return "First name is too long";
    }
    if (strlen($last_name) > 32){
        return "Last name is too long";
    }
    if (strlen($username) > 32){
        return "Username is too long";
    }
    if (strlen($email) > 320){
        return "Email is too long";
    }
    if (strlen($password) > 32){
        return "Password is too long";
    }
    //! each user will have their own table for their cards with the name of the table being the username
    //! valid MySQL table name is up to 32 characters, does not contain a dost or a forward slash
    if (strpos($username, ".") !== FALSE) {
        return "Username cannot contain a period";
    }
    if (strpos($username, "/") !== FALSE) {
        return "Username cannot contain a forward slash";
    }
    //! checking that the username is unique
    $stmt = $mysqli -> prepare("select username from users where username = ?");
    $stmt -> bind_param("s", $username);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $data = $result -> fetch_assoc();
    if ($data != NULL){
        return "ERROR: User with that username found";
    }
    //! check that consent has been given
    if (!isset($consent_given)) {
        return "Consent is required in order to sign up";
    } else {
        $consent_given = true;
    }
    //! prepare hashed and salted password for storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //! validation complete, let's add the user to the database (the users table)
    $stmt = $mysqli->prepare("insert into users(firstName, lastName, username, email, password, consentGiven) values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $first_name, $last_name, $username, $email, $hashed_password, $consent_given);
    $stmt->execute();
    if ($stmt->affected_rows != 1){
        return "An error occurred. Please try again";
    } else {
        //! if the user has been added successfully, create for them their own cards table
        $query = "create table $username (id int not null auto_increment, creationDate date not null, front varchar(320) not null, back varchar(320) not null, level int not null, nextReviewDate date not null, primary key (id))";
        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        return "Your sign up was successful. Redirecting you to the secure part of the app.";

    }
}


/**
 * Function to sign in the user
 * @param $username
 * @param $password
 * @return string
 */
function loginUser($username, $password): string
{
    $mysqli = connect();
    //! if there is at least one signed-up user (who initiated the creation of 'users' table) then we can proceed
    if ($mysqli->prepare('describe `users`;')->execute()) {
        $username = trim(htmlspecialchars($username));
        $password = trim(htmlspecialchars($password));
        //! validating input
        if($username == "" || $password == ""){
            return "Fill out your sign-in credentials $username $password";
        }
        //! looking for the user
        $query = "select username, password from users where username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        //! error-message/sign-in
        if($data == NULL){
            return "Wrong username";
        }
        if(!password_verify($password, $data['password'])){
            return "Wrong password";
        } else {
            $_SESSION['user'] = $username;
            header('location: home.php');
            return 'success';
        }
    } else {
        return "Wrong username";
    }
}

/**
 * Function to log out a user
 * @param $csrf
 * @return string
 */
function logoutUser($csrf):string
{
    if(hash_equals($_SESSION['csrf'], $csrf)){
        session_destroy();
        header('location: index.php');
        return 'success';
    } else {
        return 'csrf error';
    }
}

/**
 * Function to get number of all cards the user has in their database
 * used for pagination calculation and HOME page statistics
 * @return int
 */
function getNumberOfCards():int
{
    $mysqli = connect();
    $user = $_SESSION['user'];
    $stmt = $mysqli -> prepare("select id from $user");
    $stmt -> execute();
    $result = $stmt -> get_result();
    return mysqli_num_rows($result);
}

/**
 * Function to get number of cards ready for review
 * @return int
 */
function getNumberOfTodayCards():int
{
    $mysqli = connect();
    $user = $_SESSION['user'];
    $stmt = $mysqli -> prepare("select id from $user where nextReviewDate <= curdate()");
    $stmt -> execute();
    $result = $stmt -> get_result();
    return mysqli_num_rows($result);
}

/**
 * Function to retrieve the next card available for review
 * if there are none, empty array is returned, which is handled accordingly
 * @return array
 */
function getTodayOne(): array
{
    $mysqli = connect();
    $user = $_SESSION['user'];
    $stmt = $mysqli->prepare("select * from $user where nextReviewDate <= curdate() order by nextReviewDate desc limit 1;");
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    if ($result){
        while ($row = $result->fetch_assoc()){
            $this_row = [$row['id'], $row['front'], $row['back'], $row['level']];
            array_push($rows, $this_row);
        }
    }
    return $rows;

}


/**
 * Function to update card level and review date based on the strength of user's memory response
 * @param $card_id
 * @param $card_level
 * @param $next_review_date
 * @param $csrf
 * @return string
 */
function feedbackCard($card_id, $card_level, $next_review_date, $csrf):string
{
    if ($csrf == $_SESSION['csrf']){
        $mysqli = connect();
        $user = $_SESSION['user'];
        $query = "update $user set level=?, nextReviewDate=? where id=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('isi', $card_level, $next_review_date, $card_id);
        $stmt->execute();
        if ($stmt->get_result()){
            return 'success';
        } else {
            return 'error';
        }
    } else {
        return 'csrf error';
    }
}

/**
 * Function to add a card into signed-in user's personal table
 * @param $front
 * @param $back
 * @param $csrf
 * @return string
 */
function addCard($front, $back, $csrf):string
{
    if ($csrf == $_SESSION['csrf']) {
        $front = htmlspecialchars(trim($front));
        $back = htmlspecialchars(trim($back));
        $mysqli = connect();
        $user = $_SESSION['user'];
        $stmt = $mysqli->prepare("INSERT INTO $user (id, creationDate, front, back, level, nextReviewDate) VALUES (NULL, curdate(), ?, ?, 0, curdate());");
        $stmt->bind_param("ss", $front, $back);
        $stmt->execute();
        if ($stmt->get_result()){
            return 'success';
        } else {
            return 'error';
        }
    } else {
        return 'csrf error';
    }
}

/**
 * Function to edit a card in a signed-in user's table
 * @param $id
 * @param $front
 * @param $back
 * @param $csrf
 * @return string
 */
function editCard($id, $front, $back, $csrf): string
{
    if ($csrf == $_SESSION['csrf']) {

        //! validate and prepare safe data
        $id = htmlspecialchars(trim($id));
        $front = htmlspecialchars(trim($front));
        $back = htmlspecialchars(trim($back));
        if ($front == "" || $back == "" || $id == "") {
            return "All fields must be filled out.";
        }
        if (!is_numeric($id)) {
            return "ID must be an integer.";
        }
        $id = intval($id);
        $mysqli = connect();
        $user = $_SESSION['user'];
        //! check if a row with such ID exists
        $stmt = $mysqli->prepare("select * from $user where id=?;");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if ($data == NULL) {
            return "Card with that ID does not exist";
        }
        //! if so, update the card
        $stmt = $mysqli->prepare("update $user set front=?, back=? where id=?;");
        $stmt->bind_param("ssi", $front, $back, $id);
        $stmt->execute();
        if ($stmt->get_result()){
            return 'success';
        } else {
            return 'error';
        }
    } else {
        return "csrf error";
    }
}

/**
 * Function to create a MySQL query based on currently used sorting and filtering settings
 * @param $sorting
 * @param $filtering
 * @return string
 */
function createQueryForTotal($sorting, $filtering): string
{
    $sorting = htmlspecialchars($sorting);
    $filtering = htmlspecialchars($filtering);
    $user = $_SESSION['user'];
    $query = "select * from $user";
    //! match the filter rule and add appropriate MySQL WHERE rule
    switch ($filtering){
        case "all-levels":
            break;
        case "level-0":
            $query .= " where level=0";
            break;
        case "level-1":
            $query .= " where level=1";
            break;
        case "level-2":
            $query .= " where level=2";
            break;
        case "level-3":
            $query .= " where level=3";
            break;
        case "level-4":
            $query .= " where level=4";
            break;
        case "level-5":
            $query .= " where level=5";
            break;
        case "level-6":
            $query .= " where level=6";
            break;
        case "level-7":
            $query .= " where level=7";
            break;
        case "level-8-higher":
            $query .= " where level>=8";
            break;
    }
    //! match the sort rule and add appropriate MySQL ORDER BY rule
    switch ($sorting){
        case 'id-desc':
            $query .= " order by id desc";
            break;
        case 'id-asc':
            $query .= " order by id asc";
            break;
        case 'review-desc':
            $query .= " order by nextReviewDate desc";
            break;
        case 'review-asc':
            $query .= " order by nextReviewDate asc";
            break;
        case 'front-desc':
            $query .= " order by front desc";
            break;
        case 'front-asc':
            $query .= " order by front asc";
            break;
    }
    return $query;
}

/**
 * Function to create a query for current page in pagination
 * @param $start_from
 * @param $cards_per_page
 * @param $sorting
 * @param $filtering
 * @return string
 */
function createQueryForPage($start_from, $cards_per_page, $sorting, $filtering): string
{
    return createQueryForTotal($sorting, $filtering)." limit $start_from,$cards_per_page;";
}

/**
 * Function to retrieve cards based on sorting and filtering settings
 * @param $sorting
 * @param $filtering
 * @return false|mysqli_result
 */
function getCards($sorting, $filtering){
    $mysqli = connect();
    $stmt = $mysqli->prepare(createQueryForTotal($sorting, $filtering));
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Function to retrieve cards based on sorting and filtering settings just for the current page
 * @param $start_from
 * @param $cards_per_page
 * @param $sorting
 * @param $filtering
 * @return false|mysqli_result
 */
function getCardsForPage($start_from, $cards_per_page, $sorting, $filtering){
    $mysqli = connect();
    $stmt = $mysqli->prepare(createQueryForPage($start_from, $cards_per_page, $sorting, $filtering));
    $stmt->execute();
    return $stmt->get_result();
}