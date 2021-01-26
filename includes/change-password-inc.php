<?php

if (isset($_POST["submit"])) {
  session_start();
  if (!isset($_SESSION["clientID"])) {
    header("location: ..change-password.php?error=sessionError");
    die();
  }

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  $clientID = $_SESSION["clientID"];
  $currentPass = $_POST["currentPassword"];
  $newPass = $_POST["newPassword"];

  if (empty($currentPass) || empty($newPass)) {
    header("location: ../change-password.php?error=emptyinput");
    die();
  }

  if (correctUserPassword($conn, $clientID, $currentPass)) {
    updatePassword($conn, $newPass, $clientID);
    header("location: ../change-password.php?error=none");
    die();
  } else {
    header("location: ../change-password.php?error=wrongpassword");
    die();
  }
} else {
  header("location: ../change-password.php");
  die();
}
