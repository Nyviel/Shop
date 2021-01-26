<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="checkAdressesWrapper">
    <div style="border-bottom:1px solid #ddd;">
        <br><h2 style="color:#b88f0b;">Your saved addresses</h2><br>
    </div>
  <div class="addressesListWrapper">
    <?php
      require_once 'includes/dbh-inc.php';
      require_once 'includes/functions-inc.php';

      $query = "SELECT * FROM addresses WHERE clientID = ?";
      $clientID = $_SESSION["clientID"];

      $statement = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: check-addresses.php?error=stmtfailed");
        exit();
      }

      mysqli_stmt_bind_param($statement, "i", $clientID);
      mysqli_stmt_execute($statement);
      $resultData = mysqli_stmt_get_result($statement);

      $i = 1;
      while ($row = mysqli_fetch_assoc($resultData)) {
        $city = $row["city"];
        $street = $row["street"];
        $house_number = $row["house_number"];
        $postal_code = $row["postal_code"];
        $country = $row["country"];
        $addressID = $row["addressID"];

        echo '<div class="addressWrapper">';
            echo '<div class="addressDetails">';
                echo '<br><h3>Address nr: '.$i++.'</h3>';
                echo '<p>Country: '.$country.'</p>';
                echo '<p>City: '.$city.'</p>';
                echo '<p>Postal code: '.$postal_code.'</p>';
                echo '<p>Street: '.$street.'</p>';
                echo '<p>House number: '.$house_number.'</p>';
            echo '</div>';

            echo '<div class="deleteContactButton">';
                echo "<form class='deleteContactForm' action='delete-address.php' method='post'><br>
                      <input type='hidden' name='deleteAddressID' value='$addressID'>
                      <input type='submit' name='submit' value='Delete!'>
                      </form>";
            echo '</div>';
        echo '</div>';
      }
      if ($i === 1) {
        echo '<p>You do not have addresses yet</p>';
      }
      mysqli_stmt_close($statement);
    ?>
  </div>
</div>
</body>
</html>
