<?php
    require_once 'header.php';
?>

<?php
    require_once 'sidebar.php';
 ?>

<?php
    require_once 'includes/functions-inc.php';
    require_once 'includes/dbh-inc.php';
    echo '<div class="cartWrapper">';
        echo '<h2 style="border-bottom: 1px solid #ddd;">Your cart!</h2>';
        if (isset($_SESSION["cartArray"])) {
            $total = listCartProducts($conn);
            echo '<div style="clear:both;width:100%;height:1px;" class="clearWrapper"></div>';
            echo '<div class="summaryWrapper">';
                echo '<div class="totalCostWrapper">';
                    echo "<p><br>Total cost: $total PLN</p>";
                echo '</div>';
                echo '<div class="orderButtonWrapper">';
                    echo "<form class='orderButtonForm' action='order.php' method='post'><br>
                    <input type='submit' name='submit' value='Order!'>
                    </form>";
                echo '</div>';
                echo '<div style="clear:both;" class="clearWrapper"></div>';
            echo '</div>';
        } else {
            echo '<br><p style="font-size: 30px; line-height: 150px;">Your cart is empty!</p>';
        }
    echo '</div>';
 ?>

</body>
</html>
