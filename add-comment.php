<?php

if (!isset($_POST["submit"])) {
    header("location: index.php");
    die();
}

session_start();
if (!isset($_SESSION["clientID"])) {
    header("location: login.php");
    die();
}

require_once 'includes/functions-inc.php';
require_once 'includes/dbh-inc.php';

$productID = $_POST["productID"];
$clientID = $_SESSION["clientID"];
$message = $_POST["textarea"];

if (empty($productID) || empty($clientID)) {
    header("location: comments.php?error=emptyArgs");
    die();
}

if (empty($message)) {
    header("location: comments.php?error=emptyMessage");
    die();
}

addComment($productID, $clientID, $message, $conn);
$_SESSION["commentsProductID"] = $productID;
header("location: comments.php");
