<?php

if (isset($_POST["submit"])) {
  session_start();
  if (!isset($_SESSION["clientID"])) {
    header("location: ..change-login.php?error=sessionError");
    die();
  }

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  $clientID = $_SESSION["clientID"];
  $currentLogin = $_POST["currentLogin"];
  $newLogin = $_POST["newLogin"];

  if (empty($currentLogin) || empty($newLogin)) {
    header("location: ../change-login.php?error=emptyinput");
    die();
  }

  if (correctUserLogin($conn, $clientID, $currentLogin)) {
    updateLogin($conn, $newLogin, $clientID);
    header("location: ../change-login.php?error=none");
    die();
  } else {
    header("location: ../change-login.php?error=wronglogin");
    die();
  }
} else {
  header("location: ../change-login.php");
  die();
}
