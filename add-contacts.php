<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="addContactWrapper">
  <br><h2 style="color:#b88f0b">Add Contact</h2><br>
  <div>
    <form class="contactForm" action="includes/add-contact-inc.php" method="post">
      <input type="text" name="email" placeholder="Email..."><br>
      <input type="text" name="phone_number" placeholder="Phone number..."><br>
      <input type="submit" name="submit" value="Add"> <br>
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
      else if ($_GET["error"] == "none") {
        echo "<p>Contact successfully added</p>";
      }
    }
    ?>
  </div>
</div>
</body>
</html>
