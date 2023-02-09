<?php
//! CODE TO CHECK USERNAME AND EMAIL AVAILABILITY BEFORE SIGN UP
require 'tasks.php';
$mysqli = connect();
//! Make sure the table exists, it does not for the first user
if ($mysqli->prepare('describe `users`;')->execute()) {
    //! if the GET is coming with the usernameInput, check for username
    if (isset($_GET['usernameInput'])){
        $username = $_GET['usernameInput'];
        $stmt = $mysqli -> prepare("SELECT username FROM `users` WHERE username=?;");
        //! PREVENTING SQL INJECTION USING BIND
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result!=null){
            $num_of_rows = mysqli_num_rows($result);
            if ($num_of_rows == 1){
                echo 'found';
            } else {
                echo 'not found';
            }
        } else {
            echo 'not found';
        }
    //! else if the GET is coming with the emailInput, check for email
    } else if (isset($_GET['emailInput'])){
        $email = $_GET['emailInput'];
        $stmt = $mysqli -> prepare("SELECT username FROM `users` WHERE email=?");
        //! PREVENTING SQL INJECTION USING BIND
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result!=null){
            $num_of_rows = mysqli_num_rows($result);
            if ($num_of_rows == 1){
                echo 'found';
            } else {
                echo 'not found';
            }
        } else {
            echo 'not found';
        }
    }
    } else {
        echo 'not found';

}