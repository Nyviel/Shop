<?php
require_once 'header.php';
if (!isset($_SESSION["authorized"])) {
    header("location: index.php?error=sessionError");
    die();
} else {
    if ($_SESSION["authorized"] !== 1) {
        header("location: index.php?error=unAuthorized");
        die();
    }
}

?>


<?php
  require_once 'adminsidebar.php'
 ?>

<section class="signupwrapper">
  <br><h2 style="color:#b88f0b">Add admin</h2><br>
  <div >
    <form class="signupform" action="includes/add-admin-inc.php" method="post">
      <input type="text" name="name" placeholder="Full name..."><br>
      <input type="text" name="uid" placeholder="Username..."><br>
      <input type="password" name="password" placeholder="Password..."><br>
      <input type="password" name="rpassword" placeholder="Repeat password..."><br><br>

      <input type="submit" name="submit" value="Create admin"> <br>
    </form>
    <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill all the fields</p>";
      }
      else if ($_GET["error"] == "invalidUID") {
        echo "<p>Invalid username</p>";
      }
      else if ($_GET["error"] == "pwdMatch") {
        echo "<p>Passwords don't match</p>";
      }
      else if ($_GET["error"] == "uidExists") {
        echo "<p>Username already exists</p>";
      }
      else if ($_GET["error"] == "stmtfailed") {
        echo "<p>Something went wrong</p>";
      }
      else if ($_GET["error"] == "none") {
        echo "<p>Account successfully created</p>";
      }
    }
    ?>
  </div>
<section>
</body>
</html>
