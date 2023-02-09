<?php
//! CODE TO PREFILL GET THE DATA IN ORDER TO PREFILL EDIT CARD FORM, PROVIDED THAT A CARD WITH SUCH ID EXISTS
require 'tasks.php';
$mysqli = connect();
$user = $_SESSION['user'];
$id = intval($_GET['id']);
//! if the GET is coming with the front, return the front value, if row with that id found
if (isset($_GET['front'])){
    $stmt = $mysqli -> prepare("SELECT front FROM $user WHERE id=$id;");
    $stmt->execute();
    $result = $stmt->get_result();
    $num_of_rows = mysqli_num_rows($result);
    if ($num_of_rows == 1){
        $row = $result->fetch_assoc();
        echo $row['front'];
    } else {
        echo 'not foundHOJ';
    }
//! else if the GET is coming with the back, return the back value, if row with that id found
} else if (isset($_GET['back'])) {
    $stmt = $mysqli -> prepare("SELECT back FROM $user WHERE id=$id;");
    $stmt->execute();
    $result = $stmt->get_result();
    $num_of_rows = mysqli_num_rows($result);
    if ($num_of_rows == 1){
        $row = $result->fetch_assoc();
        echo $row['back'];
    } else {
        echo 'not foundHOJ';
    }
}
