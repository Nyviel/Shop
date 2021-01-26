<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="addAdressWrapper">
  <br><h2 style="color:#b88f0b">Add address</h2><br>
  <div>
    <form class="addressForm" action="includes/add-address-inc.php" method="post">
      <input type="text" name="country" placeholder="Country..."><br>
      <input type="text" name="city" placeholder="City..."><br>
      <input type="text" name="postal_code" placeholder="Postal code..."><br>
      <input type="text" name="street" placeholder="Street name..."><br>
      <input type="text" name="house_number" placeholder="House number..."><br>

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
        echo "<p>Address successfully added</p>";
      }
    }
    ?>
  </div>
<section>

</body>
</html>
