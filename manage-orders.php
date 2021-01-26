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

    require_once 'includes/dbh-inc.php';
    require_once 'includes/functions-inc.php';
    require_once 'adminsidebar.php'
?>

<div class="manageAllOrdersWrapper">
    <table id="ordersTable">
      <tr>
        <th>OrderID</th>
        <th>ClientID</th>
        <th>CartID</th>
        <th>StatusID</th>
        <th>AddressID</th>
        <th>ContactID</th>
        <th>Current status</th>
        <th>Update status</th>
      </tr>
    <?php
        $query = "SELECT * FROM orders";
        $resultData = mysqli_query($conn, $query);

        $i = 1;
        while($row = mysqli_fetch_assoc($resultData)) {
            $orderID = $row["orderID"];
            $clientID = $row["clientID"];
            $cartID = $row["cartID"];
            $statusID = $row["statusID"];
            $addressID = $row["addressID"];
            $contactID = $row["contactID"];

            $addressQuery = "SELECT * FROM addresses WHERE addressID = $addressID";
            $addressResult = mysqli_query($conn, $addressQuery);
            $addressRow = mysqli_fetch_assoc($addressResult);

            $contactQuery = "SELECT * FROM contacts WHERE contactID = $contactID";
            $contactResult = mysqli_query($conn, $contactQuery);
            $contactRow = mysqli_fetch_assoc($contactResult);

            $statusQuery = "SELECT * FROM statuses WHERE statusID = $statusID";
            $statusResult = mysqli_query($conn, $statusQuery);
            $statusRow = mysqli_fetch_assoc($statusResult);
            $statusMsg = $statusRow["status_message"];

            echo "<tr>
                <td>$orderID</td>
                <td>$clientID</td>
                <td>$cartID</td>
                <td>$statusID</td>
                <td>$addressID</td>
                <td>$contactID</td>
                <td>$statusMsg</td>
                <td>
                    <form class='signupform' action='update-order.php' method='post'>
                        <br><select name='orderUpdateSelect'>
                            <option value='pending'>Pending</option>
                            <option value='sent'>Sent</option>
                            <option value='completed'>Completed</option>
                        </select>
                        <input type='hidden' name='statusID' value='$statusID'>
                        <input type='submit' name='submit' value='Update!'>
                    </form>
                </td>
            </tr>";
        }
     ?>
    </table>
</div>
