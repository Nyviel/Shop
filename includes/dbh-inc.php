<?php

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "phpprojectDB";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
  die("Connection to database failed:" . mysqli_connect_error());
}
