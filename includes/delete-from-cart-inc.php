<?php

session_start();

if (!isset($_POST["submit"])) {
    header("location: ../check-cart.php");
    die();
}

if (!isset($_SESSION["cartArray"])) {
    header("location: ../check.cart.php?error=cartNotSet");
    die();
}

$productID = $_POST["productID"];
$cartArray = $_SESSION["cartArray"];

unset($cartArray["$productID"]);
$_SESSION["cartArray"] = $cartArray;
if(empty($_SESSION["cartArray"])) {
    unset($_SESSION["cartArray"]);
}

header("location: ../check-cart.php");
