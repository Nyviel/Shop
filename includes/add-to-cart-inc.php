<?php
session_start();

if(!isset($_POST["submit"])) {
    header("location: ../index.php?error=cartError");
    die();
}

$productID = $_POST["productID"];
$quantity = 1;

if (!isset($_SESSION["cartArray"])) {
    $cartArray = array();
    $cartArray[$productID] = $quantity;
    $_SESSION["cartArray"] = $cartArray;
} else {
    $cartArray = $_SESSION["cartArray"];
    if (isset($cartArray[$productID])) {
        $currentQuantity = $cartArray[$productID];
        $cartArray[$productID] = $currentQuantity+1;
    } else {
        $cartArray[$productID] = $quantity;
    }
    $_SESSION["cartArray"] = $cartArray;
}
