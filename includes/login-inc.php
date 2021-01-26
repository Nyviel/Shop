<?php

if (isset($_POST["submit"])) {
  $username = $_POST["uid"];
  $pwd = $_POST["password"];

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  if (empty($username) || empty($pwd)) {
    header("location: ../login.php?error=emptyinput");
    exit();
  }

  loginUser($conn, $username, $pwd);

} else {
  header("location: ../index.php");
  exit();
}
