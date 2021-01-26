<?php

if (isset($_POST["submit"])) {
  $name = $_POST["name"];
  $username = $_POST["uid"];
  $pwd = $_POST["password"];
  $rpwd = $_POST["rpassword"];

  require_once 'dbh-inc.php';
  require_once 'functions-inc.php';

  if (emptyInputSignup($name, $username, $pwd, $rpwd) !== false) {
    header("location: ../signup.php?error=emptyinput");
    exit();
  }

  if (invalidUID($username) !== false) {
    header("location: ../signup.php?error=invalidUID");
    exit();
  }

  if (pwdMatch($pwd, $rpwd) !== true) {
    header("location: ../signup.php?error=pwdMatch");
    exit();
  }

  if (uidExists($conn, $username) !== false) {
    header("location: ../signup.php?error=uidExists");
    exit();
  }

  createUser($conn, $name, $username, $pwd, 0);

} else {
  header("location: ../signup.php");
}
