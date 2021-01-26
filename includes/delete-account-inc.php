<?php
session_start();
if (isset($_SESSION["clientID"]) and isset($_SESSION["clientUID"])) {
    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    if (uidExists($conn, $_SESSION["clientUID"])) {
        if (deleteUser($_SESSION["clientID"], $conn)) {
            session_unset();
            session_destroy();
            header("location: ../index.php");
            die();
        } else {
            header("location: ../profile.php?error=deleteFailed");
            die();
        }
    } else {
        header("location ../profile.php?error=UIDNotFound");
        die();
    }
} else {
    header("location: ../index.php");
    die();
}
