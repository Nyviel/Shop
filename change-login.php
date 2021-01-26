<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="changeLoginWrapper">
  <br><h2 style="color:#b88f0b">Change login</h2><br>
  <div>
    <form class="loginForm" action="includes/change-login-inc.php" method="post">
      <input type="text" name="currentLogin" placeholder="Login..."><br>
      <input type="text" name="newLogin" placeholder="New login..."><br>

      <input type="submit" name="submit" value="Change it!">
    </form>
    <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "sessionError") {
        echo "<p>Session error occured</p>";
      }
      else if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill all the fields</p>";
      }
      else if ($_GET["error"] == "stmtfailed") {
        echo "<p>Something went wrong</p>";
      }
      else if ($_GET["error"] == "wronglogin") {
        echo "<p>Wrong login</p>";
      }
      else if ($_GET["error"] == "none") {
        echo "<p>Login successfully changed!</p>";
      }
    }
    ?>
  </div>
</div>
</body>
</html>
