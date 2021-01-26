<?php

if (isset($_POST["submit"])) {
  session_start();
  if (!isset($_SESSION["clientID"])) {
    header("location: ../add-addresses.php?error=sessionError");
    die();
  }

  $clientID = $_SESSION["clientID"];
  $email = $_POST["email"];
  $phone_number = $_POST["phone_number"];

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  if (empty($email) || empty($phone_number)) {
      header("location: ../add-contacts.php?error=invalidData2");
      die();
  }

  if (invalidEmail($email)) {
      header("location: ../add-contacts.php?error=invalidData");
      die();
  }

  addNewContact($conn, $email, $phone_number, $clientID);
} else {
  header("location: ../add-contacts.php");
  die();
}
