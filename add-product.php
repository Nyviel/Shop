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

 <section class="signupwrapper">
   <br><h2 style="color:#b88f0b">Add Product</h2><br>
   <div >
     <form class="signupform" action="includes/add-product-inc.php" method="post">
       <input type="text" name="name" placeholder="Product name..."><br>
       <input type="text" name="price" placeholder="Price..."><br>
       <input type="text" name="path_to_img" placeholder="Path to the img(img/...)..."><br>
       <input type="text" name="description" placeholder="Description..."><br>
       <select name="categorySelect">
           <?php
                $categoriesList = getCategories($conn);
                foreach ($categoriesList as $category) {
                    echo "<option value='$category'>$category</option>";
                }
            ?>
       </select>
       <input type="submit" name="submit" value="Add!"> <br>
     </form>
     <?php
     if (isset($_GET["error"])) {
       if ($_GET["error"] == "emptyinput") {
         echo "<p>Fill all the fields</p>";
       }
       else if ($_GET["error"] == "stmtfailed") {
         echo "<p>Something went wrong</p>";
       }
       else if ($_GET["error"] == "none") {
         echo "<p>Product successfully added</p>";
       }
     }
     ?>
   </div>
</section>
 </body>
 </html>
