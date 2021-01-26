<?php
session_start();

if (!isset($_POST["submit"])) {
    header("location: index.php");
    die();
}

if (!isset($_SESSION["clientID"])) {
    header("location:index.php?error=sessionError");
    die();
}

if (!isset($_SESSION["cartArray"])) {
    header("location:index.php?error=cartError");
    die();
}

$addressID = $_POST["addressID"];
$contactID = $_POST["contactID"];
$clientID = $_SESSION["clientID"];
$cartArray = $_SESSION["cartArray"];

require_once 'includes/functions-inc.php';
require_once 'includes/dbh-inc.php';

$cartID = getCartKey($conn);

$statusID = createStatus($conn);
createCart($clientID, $cartArray, $cartID, $conn);
createOrder($clientID, $cartID, $statusID, $addressID, $contactID, $conn);

header("location: order-created.php");
