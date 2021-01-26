<?php

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $path = $_POST["path_to_img"];
    $description = $_POST["description"];
    $category = $_POST["categorySelect"];

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    if (emptyInputSignup($name, $price, $path, $description) !== false) {
        header("location: ../add-product.php?error=emptyinput");
        exit();
    }

    if (empty($category)) {
        header("location: ../add-product.php?error=emptyinput");
        exit();
    }

    if (addProduct($name, $price, $path, $description, $category, $conn)) {
        header("location: ../add-product.php?error=none");
    } else {
        header("location: ../add-product.php?error=stmtfailed");
    }
} else {
    header("location: ../index.php");
}
