<?php

if (!isset($_POST["submit"])) {
    header("location: ../check-cart.php");
    die();
}

if (!isset($_POST["deleteAddressID"])) {
    header("location: check-addresses.php?error=addressError");
    die();
}

$addressID = $_POST["deleteAddressID"];

require_once 'includes/functions-inc.php';
require_once 'includes/dbh-inc.php';

deleteAddress($addressID, $conn);

header("location: check-addresses.php");
