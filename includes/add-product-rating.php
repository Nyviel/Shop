<?php
session_start();

if(!isset($_POST["submit"])) {
    header("location: ../index.php?error=productError");
    die();
}

if(!isset($_POST["ratingProductID"])) {
    header("location: ../index.php?error=productError");
    die();
}

if(!isset($_SESSION["clientID"])) {
    header("location: ../login.php");
    die();
}

$productID = $_POST["ratingProductID"];
$score = $_POST["userRatingSelect"];
$clientID = $_SESSION["clientID"];

require_once 'dbh-inc.php';
require_once 'functions-inc.php';

if (userRatingExists($productID, $clientID, $conn)) {
    deleteRating($productID, $clientID, $conn);
}
addRating($productID, $clientID, $score, $conn);
$_SESSION["commentsProductID"] = $productID;
header("location: ../comments.php");
