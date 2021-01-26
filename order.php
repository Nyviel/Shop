<?php
    require_once 'header.php';
?>

<?php
    require_once 'sidebar.php';
 ?>

<?php
    if (!isset($_SESSION["clientID"])) {
        header("location: login.php");
        die();
    }

    require_once 'includes/functions-inc.php';
    require_once 'includes/dbh-inc.php';
    $clientID = $_SESSION["clientID"];
    $addressesArray = getAddresses($clientID, $conn);
    $contactsArray = getContacts($clientID, $conn);
    echo '<div class="orderWrapper">';
        echo '<div style="border-bottom: 1px solid #ddd;">';
            echo '<h1 style="color:#b88f0b; padding: 10px 0;">Select your order details</h1>';
        echo '</div>';


        echo "<form class='orderForm' action='summarize-order.php' method='post'>";
            echo '<div class="addressSelectWrapper">';
                echo '<h2>Pick your address</h2>';
                    echo '<select style="width: 90%;" name="addressSelect">';
                        foreach ($addressesArray as $addressID => $address) {
                            echo "<option value='$addressID'>$address</option>";
                        }
                    echo '</select><br>';
            echo '</div>';

            echo '<div class="contactSelectWrapper">';
                echo '<h2>Pick your contact information</h2>';
                    echo '<select style="width: 90%;" name="contactSelect">';
                        foreach ($contactsArray as $contactID => $contact) {
                            echo "<option value='$contactID'>$contact</option>";
                        }
                    echo '</select><br>';
            echo '</div>';

            echo "<input type='submit' name='submit' value='Finalize!'>";
         echo '</form>';
    echo '</div>';
 ?>

</body>
</html>
