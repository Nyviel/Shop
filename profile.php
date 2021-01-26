<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

<div class="profileFieldWrapper">
  <div class="profileWrapper">
    <div class="userIconWrapper">
      <img src="img/usericon.png" alt="Icon">
    </div>
    <div class="userNameWrapper">
      <?php
          require_once 'includes/dbh-inc.php';
          require_once 'includes/functions-inc.php';

          if (isset($_SESSION["clientID"])) {
            $clientName = $_SESSION["name"];
            echo '<p>'.$clientName.'<p>';
          } else {
            echo '<p>Failed to get username<p>';
          }
       ?>
    </div>
    <div class="userButtonsWrapper">
      <a href="check-contacts.php"><button type="button">Check contacts</button></a>
      <a href="add-contacts.php"><button type="button">Add contacts</button></a>
      <a href="check-addresses.php"><button type="button">Check addresses</button></a>
      <a href="add-addresses.php"><button type="button">Add addresses</button></a>
    </div>
    <div class="userInformationWrapper">
      <p>User information</p>
    </div>
    <div class="bonusButtonsWrapper">
      <a href="change-password.php"><button type="button">Change password</button></a>
      <a href="change-login.php"><button type="button">Change login</button></a>
      <a href="view-orders.php"><button type="button">View my orders</button></a>
    </div>
    <div class="userDeleteWrapper">
      <a href="includes/delete-account-inc.php"><button type="button">Delete account</button></a>
    </div>
  </div>
</div>
</body>
</html>
