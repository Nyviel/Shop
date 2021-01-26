<?php
  require_once 'header.php'
 ?>

<?php
  require_once 'sidebar.php'
 ?>

 <div class="checkContactsWrapper">
     <div style="border-bottom:1px solid #ddd;">
         <br><h2 style="color:#b88f0b;">Your saved contacts</h2><br>
     </div>
   <div class="contactsListWrapper">
     <?php
       require_once 'includes/dbh-inc.php';
       require_once 'includes/functions-inc.php';

       $query = "SELECT * FROM contacts WHERE clientID = ?";
       $clientID = $_SESSION["clientID"];

       $statement = mysqli_stmt_init($conn);

       if (!mysqli_stmt_prepare($statement, $query)) {
         header("location: check-contacts.php?error=stmtfailed");
         exit();
       }

       mysqli_stmt_bind_param($statement, "i", $clientID);
       mysqli_stmt_execute($statement);
       $resultData = mysqli_stmt_get_result($statement);

       $i = 1;
       while ($row = mysqli_fetch_assoc($resultData)) {
            $email = $row["email"];
            $phone_number = $row["phone_number"];
            $contactID = $row["contactID"];

            echo '<div class="contactWrapper">';
                echo '<div class="contactDetails">';
                    echo '<br><h3>Contact nr: '.$i++.'</h3>';
                    echo '<p>Email: '.$email.'</p>';
                    echo '<p>Phone number: '.$phone_number.'</p>';
                echo '</div>';

                echo '<div class="deleteContactButton">';
                    echo "<form class='deleteContactForm' action='delete-contact.php' method='post'><br>
                          <input type='hidden' name='deleteContactID' value='$contactID'>
                          <input type='submit' name='submit' value='Delete!'>
                          </form>";
                echo '</div>';
            echo '</div>';
       }
       if ($i === 1) {
         echo '<br><p style="color:#b88f0b;">You do not have contacts yet</p>';
       }
       mysqli_stmt_close($statement);
     ?>
   </div>
 </div>
</body>
</html>
