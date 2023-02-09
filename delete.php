<?php
require 'tasks.php';
//! code to delete a card with a certain ID
if (isset($_GET['csrfToken']) && isset($_SESSION['csrf']) && isset($_GET['id'])){
    if ($_GET['csrfToken'] == $_SESSION['csrf']){
        $mysqli = connect();
        $user = $_SESSION['user'];
        $card_id = $_GET['id'];
        $stmt = $mysqli->prepare("delete from $user where id=?;");
        $stmt->bind_param('i', $card_id);
        $stmt->execute();
        header('location:cards.php');
        exit();
    }
}