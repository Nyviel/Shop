<?php

if (!isset($_POST["submit"])) {
    header("location: ../check-cart.php");
    die();
}

if (!isset($_POST["deleteContactID"])) {
    header("location: check-contacts.php?error=contactError");
    die();
}

$contactID = $_POST["deleteContactID"];

require_once 'includes/functions-inc.php';
require_once 'includes/dbh-inc.php';

deleteContact($contactID, $conn);


header("location: check-contacts.php");
