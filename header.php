<?php
  session_start();
 ?>

<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/mystyles.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

  <div class="headerWrapper">
    <nav class="navMainBar">
      <a href="index.php"><img class="logoImg" src="img/logo.png" alt="Logo"></a>
      <ul class="ulMainBar">
        <li><a href="index.php">Home</a></li>
        <li><a href="aboutus.php">About us</a></li>
        <li><a href="contactus.php">Contact us</a></li>
        <?php
          if (isset($_SESSION["clientID"])) {
            echo "<li><a href='profile.php'>Profile</a></li>";
            if (isset($_SESSION["authorized"]) && $_SESSION["authorized"] === 1) {
                echo "<li><a href='manage-orders.php'>Admin Panel</a></li>";
            }
            echo "<li><a href='includes/logout-inc.php'>Log out</a></li>";

          } else {
            echo "<li><a href='signup.php'>Sign up</a></li>";
            echo "<li><a href='login.php'>Log in</a></li>";
          }
         ?>
      </ul>
      <ul style='float:right;' class='ulCartBar'>
          <li id='cart'><a href='check-cart.php'><img class='cartImg' src='img/cart.png'></a></li>
      </ul>
    </nav>
  </div>
