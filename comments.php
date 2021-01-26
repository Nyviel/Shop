<?php
    require_once 'header.php';
?>

<?php
    require_once 'sidebar.php';
?>

<div class="reviewWrapper">
  <div>
      <?php
        require_once 'includes/functions-inc.php';
        require_once 'includes/dbh-inc.php';
        $productID;
        if (isset($_POST["productID"])) $productID = $_POST["productID"];
        else if (isset($_SESSION["commentsProductID"])) $productID = $_SESSION["commentsProductID"];
        else {
            header("location: index.php?error=sessionError");
            die();
        }
        echo '<div class="commentsProductWrapper">';
            $productInfo = getProductRow($productID, $conn);
            if ($productInfo === false) die("Failed to get product from database");
            $price = $productInfo["price"];
            $productName = $productInfo["name"];
            $path_to_img = $productInfo["path_to_img"];
            echo "<h1>$productName</h1>";
            echo "<img src='$path_to_img'></img>";
        echo '</div>';

        echo '<div class="commentsRatingWrapper">';
            echo '<div class="globalRating">';
                echo '<h2>Overall Rating</h2><br>';
                    $rating = getRatingForProduct($productID, $conn);
                    $totalVotes = getTotalVotesForProduct($productID, $conn);
                    echo "<p>$rating/5 ($totalVotes)</p>";
                echo '<br>';
            echo '</div>';

            echo '<div class="userRating">';
                echo "<h2>Your Rating</h2>";
                echo '<form action="includes/add-product-rating.php" method="post">';
                    $userRating = 0;
                    if (isset($_SESSION["clientID"])) {
                        $userRating = getUserRatingForProduct($productID, $_SESSION["clientID"], $conn);
                    }
                    if (!$userRating) echo '<p>You have not rated yet!</p><br>';
                    else echo "<br><p>$userRating/5</p>";
                    echo '<br>';
                    echo '<h2>Update your rating!</h2>';
                    echo '<select style="width: 25%;" name="userRatingSelect">';
                        echo '<option value="1">1</option>';
                        echo '<option value="2">2</option>';
                        echo '<option value="3">3</option>';
                        echo '<option value="4">4</option>';
                        echo '<option value="5">5</option>';
                    echo '</select><br>';
                    echo '<input type="hidden" name="ratingProductID" value="'.$productID.'"/>';
                    echo '<input type="submit" name="submit" value="Send my rating!"/>';
                echo '</form>';
            echo '</div>';

        echo '</div>';

        echo '<div class="commentsCommentsWrapper">';
            echo '<div class="commentsUserWrapper">
                    <h2  style="padding-top:10px;">What do you think about the '.$productName.'</h2>
                      <form class="userCommentForm" action="add-comment.php" method="post">
                          <textarea class="getMessage" name="textarea" maxlength="400" placeholder="Add your comment..."></textarea><br>';
                            if (isset($_GET["error"])) {
                                $error = $_GET["error"];
                                if ($error === "emptyMessage") {
                                    echo 'Comment is empty!';
                                    echo '<br>';
                                }
                            }
                          echo '<input type="hidden" name="productID" value="'.$productID.'">
                          <input type="submit" name="submit" value="Comment!">
                      </form>
                  </div>';
            echo '<div class="allCommentsWrapper">';
                echo '<h2 style="padding-top:10px;">What others think about the '.$productName.'</h2><br>';
                listComments($productID, $conn);
            echo '</div>';
        echo '</div>';
       ?>

  </div>
</div>
