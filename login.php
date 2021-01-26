<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<section class="loginwrapper">
  <br><h2 style="color:#b88f0b">Log In</h2><br>
  <div >
    <form class="loginform" action="includes/login-inc.php" method="post">
      <input type="text" name="uid" placeholder="Username..."><br>
      <input type="password" name="password" placeholder="Password..."><br>

      <input type="submit" name="submit" value="Login">
    </form>
    <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill all the fields</p>";
      }
      else if ($_GET["error"] == "stmtfailed") {
        echo "<p>Something went wrong</p>";
      }
      else if ($_GET["error"] == "wrongpassword") {
        echo "<p>Wrong password</p>";
      }
      else if ($_GET["error"] == "userdoesntexist") {
        echo "<p>User doesn't exist</p>";
      }
    }
    ?>
  </div>
<section>
</body>
</html>
