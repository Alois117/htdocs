<?php
session_start();

function checkSession() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: Sign in.php');
        exit();
    }
}
?>
