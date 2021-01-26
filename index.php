<?php
  require 'header.php';
?>

<?php
  require 'sidebar.php';
?>

<div class="indexWrapper">
  <div>
      <?php
        require_once 'includes/functions-inc.php';
        $category = "";
        if (isset($_GET["category"])) {
            $category = $_GET["category"];
        } else {
            $category = "all";
        }
        listProducts($category);
       ?>
  </div>
</div>


</body>

</html>
