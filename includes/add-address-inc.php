<?php

if (isset($_POST["submit"])) {
  session_start();
  if (!isset($_SESSION["clientID"])) {
    header("location: ../add-addresses.php?error=sessionError");
    die();
  }
  $clientID = $_SESSION["clientID"];
  $country = $_POST["country"];
  $city = $_POST["city"];
  $postal_code = $_POST["postal_code"];
  $street = $_POST["street"];
  $house_number = $_POST["house_number"];

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  if (!validateAddressData($country, $city, $postal_code, $street, $house_number)) {
      header("location: ../add-addresses.php?error=invalidData");
      die();
  }

  addNewAddress($conn, $country, $city, $postal_code, $street, $house_number, $clientID);
} else {
  header("location: ../add-addresses.php");
  die();
}
