<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="changePassWrapper">
  <br><h2 style="color:#b88f0b">Change password</h2><br>
  <div>
    <form class="passForm" action="includes/change-password-inc.php" method="post">
      <input type="password" name="currentPassword" placeholder="Password..."><br>
      <input type="password" name="newPassword" placeholder="New password..."><br>

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
      else if ($_GET["error"] == "wrongpassword") {
        echo "<p>Wrong password</p>";
      }
      else if ($_GET["error"] == "none") {
        echo "<p>Password successfully changed!</p>";
      }
    }
    ?>
  </div>
</div>
</body>
</html>
