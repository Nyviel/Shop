<?php

function validateAddressData($country, $city, $postal_code, $street, $house_number) {
    if (empty($country) || empty($city) || empty($postal_code) || empty($street) || empty($house_number)) {
        return false;
    }

  return true;
}

function emptyInputSignup($name, $username, $pwd, $rpwd) {
    return (empty($name) || empty($username) || empty($pwd) || empty($rpwd));
}

function invalidUID($username) {
    return !preg_match("/^[a-zA-Z0-9]*$/", $username);
}

function invalidEmail($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function pwdMatch($pwd, $rpwd) {
    return $pwd === $rpwd;
}

function validPostal($postal_code) {
    $postalRegex = "^\\d{2}[- ]{0,1}\\d{3}$";
    return preg_match($postalRegex, $postal_code);
}

function uidExists($conn, $username) {
    $query = "SELECT * FROM clients WHERE clientUID = ?;";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    if ($row = mysqli_fetch_assoc($resultData)) {
    return $row;
    } else {
    return false;
    }

    mysqli_stmt_close($statement);
}

function correctUserLogin($conn, $clientID, $currentLogin) {
    $query = "SELECT * FROM clients WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        die();
    }

    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($resultData);
    return $row["clientUID"] === $currentLogin;
}

function correctUserPassword($conn, $clientID, $currentPass) {
    $query = "SELECT * FROM clients WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        die();
    }

    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($resultData);

    $currentHash = $row["clientPD"];

    return password_verify($currentPass, $currentHash);
}

function createUser($conn, $name, $username, $pwd, $isAdmin) {
    $query = "INSERT INTO clients (name, clientPD, clientUID, isAdmin) values (?, ?, ?, $isAdmin);";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($statement, "sss", $name, $hashPwd, $username);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    header("location: ../signup.php?error=none");
    exit();
}

function loginUser($conn, $username, $pwd) {
    $userExists = uidExists($conn, $username);
    if ($userExists) {
        $pwdHash = $userExists["clientPD"];
        $checkPwd = password_verify($pwd, $pwdHash);
        if ($checkPwd === false) {
            header("location: ../login.php?error=wrongpassword");
            exit();
        } else if ($checkPwd === true) {
            session_start();
            $_SESSION["clientID"] = $userExists["clientID"];
            $_SESSION["clientUID"] = $userExists["clientUID"];
            $_SESSION["name"] = $userExists["name"];
            if (isAdmin($userExists["clientID"], $conn)) $_SESSION["authorized"] = 1;
            header("location: ../index.php");
            exit();
        }
    } else {
        header("location: ../login.php?error=userdoesntexist");
        exit();
    }
}

function addNewAddress($conn, $country, $city, $postal_code, $street, $house_number, $clientID) {
    $query = "INSERT INTO addresses(clientID, city, street, house_number, postal_code, country) VALUES(?,?,?,?,?,?)";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../add-addresses.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "isssss", $clientID, $city, $street, $house_number, $postal_code, $country);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    header("location: ../add-addresses.php?error=none");
    exit();
}

function addNewContact($conn, $email, $phone_number, $clientID) {
    $query = "INSERT INTO contacts(clientID, email, phone_number) VALUES(?,?,?)";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../add-contacts.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "iss", $clientID, $email, $phone_number);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    header("location: ../add-contacts.php?error=none");
    exit();
}

function updateLogin($conn, $newLogin, $clientID) {
    $query = "UPDATE clients SET clientUID = ? WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        header("location: ../change-login.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "si", $newLogin, $clientID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function updatePassword($conn, $newPassword, $clientID) {
      $query = "UPDATE clients SET clientPD = ? WHERE clientID = ?";
      $statement = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($statement, $query)) {
          header("location: ../change-password.php?error=stmtfailed");
          exit();
      }

      $pwdHash = password_hash($newPassword, PASSWORD_DEFAULT);
      mysqli_stmt_bind_param($statement, "si", $pwdHash, $clientID);
      mysqli_stmt_execute($statement);
      mysqli_stmt_close($statement);
}

function deleteUser($clientID, $conn) {
    $queryClients = "DELETE FROM clients WHERE clientID = ?";
    $queryAddresses = "DELETE FROM addresses WHERE clientID = ?";
    $queryContacts = "DELETE FROM contacts WHERE clientID = ?";
    $queryCarts = "DELETE FROM carts WHERE clientID = ?";
    $queryComments = "DELETE FROM comments WHERE clientID = ?";
    $queryRatings = "DELETE FROM ratings WHERE clientID = ?";

    return (delete($queryClients, $clientID, $conn)   and
            delete($queryAddresses, $clientID, $conn) and
            delete($queryContacts, $clientID, $conn)  and
            delete($queryCarts, $clientID, $conn)     and
            delete($queryComments, $clientID, $conn)  and
            delete($queryRatings, $clientID, $conn));
}

function delete($query, $clientID, $conn) {
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    return true;
}

function validCategory($category, $conn) {
    $query = "SELECT * FROM categories";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    while ($row = mysqli_fetch_assoc($resultData)) {
        $rowCat = $row["category_name"];
        if ($rowCat == $category) return true;
    }

    mysqli_stmt_close($statement);
    return false;
}

function getProducts($category, $conn) {
    if ($category == "all") {
        $query = "SELECT * FROM products";
        $statement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($statement, $query)) {
            return false;
        }
        mysqli_stmt_execute($statement);
        return mysqli_stmt_get_result($statement);
    } else {
        $query = "SELECT * FROM products p
        INNER JOIN categories c
        ON p.CategoryID = c.CategoryID
        WHERE category_name = ?";

        $statement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($statement, $query)) {
            return false;
        }

        mysqli_stmt_bind_param($statement, "s", $category);
        mysqli_stmt_execute($statement);
        return mysqli_stmt_get_result($statement);
    }
}

function listProducts($category) {
    if (!empty($category)) {
        require_once 'dbh-inc.php';
        if (validCategory($category, $conn) || $category=="all") {
            $resultData = getProducts($category, $conn);

            while ($row = mysqli_fetch_assoc($resultData)) {
                $productID = $row["productID"];
                $productName = $row["name"];
                $price = $row["price"];
                $path_to_img = $row["path_to_img"];
                $description = $row["description"];

                echo '<div class="productWrapper">';
                    echo '<div class="productInfoWrapper">';
                        echo '<div class="productNameWrapper">';
                            echo '<p>'.$productName.'</p>';
                        echo '</div>';
                        echo '<div class="productImgWrapper">';
                            echo '<img class="productImg" src="'.$path_to_img.'"></img><br>';
                        echo '</div>';
                        echo '<div class="productPriceWrapper">';
                            echo '<p>'.$price.' PLN</p>';
                        echo '</div>';
                        echo '<div class="productReviewWrapper">';
                            echo '<div class="productCommentsWrapper">';
                                echo "<form class='cartform' action='comments.php' method='post'>
                                        <input type='hidden' name='productID' value='$productID'>
                                        <input type='submit' name='submit' value='Comments and Rating!'>
                                      </form>";
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="productDescWrapper">';
                        echo '<h2>Description:</h2><br>';
                        $tokens = explode(",", $description);
                        $i = 0;
                        foreach($tokens as &$token) {
                            echo $token;
                            echo ' <br>';
                        }
                        unset($token);
                    echo '</div>';
                    echo '<div class="productCartWrapper">';
                        echo "<iframe name='dummyframe' id='dummyframe' style='display:none;'></iframe>";
                        echo "<form class='cartform' action='includes/add-to-cart-inc.php' method='post' target='dummyframe'><br>
                                <input type='hidden' name='productID' value='$productID'>
                                <input type='submit' name='submit' value='Add to cart!'>
                              </form>";
                    echo '</div>';
                echo '</div>';
            }
            die();
        } else {
            echo 'Invalid category';
            die();
        }
    } else {
        echo 'Category is empty';
        die();
    }
}

function listCartProducts($conn) {
    $cartArray = $_SESSION["cartArray"];
    $totalPrice = 0;
    if (isset($cartArray)) {
        foreach ($cartArray as $productID => $quantity) {
            $productInfo = getProductRow($productID, $conn);
            if ($productInfo === false) die("Failed to get product from database");
            $price = $productInfo["price"];
            $productName = $productInfo["name"];
            $path_to_img = $productInfo["path_to_img"];
            $totalPrice += ($price*$quantity);
            echo '<div class="cartProductWrapper">';
                echo '<div class="cartProductImgWrapper">';
                    echo '<img class="productImg" src="'.$path_to_img.'"></img><br>';
                echo '</div>';
                echo '<div class="cartProductPriceWrapper">';
                    echo "<p style='width:200px;'>$productName</p>";
                    echo "<p style='width:200px;'>Quantity: $quantity</p>";
                    echo "<p style='width:200px;'>Price: $price PLN </p>";
                echo '</div>';
                echo "<form class='deleteProdForm' action='includes/delete-from-cart-inc.php' method='post'><br>
                <input type='hidden' name='productID' value='$productID'>
                <input type='submit' name='submit' value='X'>
                </form>";
            echo '</div>';
        }
        return $totalPrice;
    }
    return 0;
}

function getProductRow($productID, $conn) {
    $query = "SELECT * FROM products WHERE productID = ?";

    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $productID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    return mysqli_fetch_assoc($resultData);
}


function addComment($productID, $clientID, $message, $conn) {
    $query = "INSERT INTO comments(productID, clientID, message) VALUES (?,?,?)";

    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "iis", $productID, $clientID, $message);
    mysqli_stmt_execute($statement);
    return true;
}

function listComments($productID, $conn) {
    $query = "SELECT * FROM comments
    INNER JOIN clients
    on comments.clientID = clients.clientID
    WHERE productID = ?";

    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $productID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    while ($row = mysqli_fetch_assoc($resultData)) {
        $message = $row["message"];
        $name = $row["name"];

        echo '<div class="userMessage">';
            echo '<div class="userNick">';
                echo "<p>$name</p>";
            echo '</div>';

            echo '<div class="userMessageContent">';
                echo '<textarea name="userMsg" disabled="true" maxlength="400">'.$message.'</textarea><br>';
            echo '</div>';
        echo '</div>';
    }
}

function getRatingForProduct($productID, $conn) {
    $rating = 0;
    $query = "SELECT * FROM ratings WHERE productID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return 0;
    }

    mysqli_stmt_bind_param($statement, "i", $productID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    $scores = 0;
    $totalSum = 0;
    while ($row = mysqli_fetch_assoc($resultData)) {
        $score = $row["score"];
        $totalSum += $score;
        $scores++;
    }
    if ($scores > 0 and $totalSum > 0) $rating = $totalSum / $scores;
    return $rating;
}

function userRatingExists($productID, $clientID, $conn) {
    $query = "SELECT * FROM ratings WHERE clientID = ? AND productID = ?";

    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "ii", $clientID, $productID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    return !empty(mysqli_fetch_assoc($resultData));
}

function deleteRating($productID, $clientID, $conn) {
    $query = "DELETE FROM ratings WHERE clientID = ? AND productID = ?";

    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "ii", $clientID, $productID);
    mysqli_stmt_execute($statement);
}

function addRating($productID, $clientID, $score, $conn) {
    $query = "INSERT INTO ratings(productID, clientID, score) VALUES (?,?,?)";

    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "iii", $productID, $clientID, $score);
    mysqli_stmt_execute($statement);
}

function getUserRatingForProduct($productID, $clientID, $conn) {
    $rating = 0;
    $query = "SELECT * FROM ratings WHERE productID = ? and clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return 0;
    }

    mysqli_stmt_bind_param($statement, "ii", $productID, $clientID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($resultData);
    if (isset($row["score"])) $rating = $row["score"];
    return $rating;
}

function getTotalVotesForProduct($productID, $conn) {
    $query = "SELECT count(score) FROM ratings WHERE productID = ?";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return 0;
    }
    mysqli_stmt_bind_param($statement, "i", $productID);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($resultData);
    $totalVotes = $row["count(score)"];
    return $totalVotes;
}

function deleteContact($contactID, $conn) {
    $query = "DELETE FROM contacts WHERE contactID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $contactID);
    mysqli_stmt_execute($statement);
    return true;
}

function deleteAddress($addressID, $conn) {
    $query = "DELETE FROM addresses WHERE addressID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $addressID);
    mysqli_stmt_execute($statement);
    return true;
}

function getAddresses($clientID, $conn) {
    $query = "SELECT * FROM addresses WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    $addresses = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $country = $row["country"];
        $city = $row["city"];
        $street = $row["street"];
        $postal_code = $row["postal_code"];
        $house_number = $row["house_number"];
        $addressID = $row["addressID"];
        $addresses[$addressID] = 'Country:'.$country.' City:'.$city.' Street:'.$street.' Postal:'.$postal_code.' House:'.$house_number;
    }
    mysqli_stmt_close($statement);
    return $addresses;
}

function getContacts($clientID, $conn) {
    $query = "SELECT * FROM contacts WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    $contacts = array();
    while ($row = mysqli_fetch_assoc($resultData)) {
        $email = $row["email"];
        $phone_number = $row["phone_number"];
        $contactID = $row["contactID"];
        $contacts[$contactID] = 'Email:' . $email . ' Phone:' . $phone_number;
    }
    mysqli_stmt_close($statement);
    return $contacts;
}

function getAddress($addressID, $conn) {
    $query = "SELECT * FROM addresses WHERE addressID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $addressID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_assoc($resultData);
}

function getContact($contactID, $conn) {
    $query = "SELECT * FROM contacts WHERE contactID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "i", $contactID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return mysqli_fetch_assoc($resultData);
}

function cartEmpty($conn) {
    $query = "SELECT * FROM carts";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    return empty(mysqli_fetch_assoc($resultData));
}

function getCartKey($conn) {
    if (cartEmpty($conn)) return 1;

    $query = "SELECT max(cartID) FROM carts";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);
    $row = mysqli_fetch_assoc($resultData);
    $cartID = $row["max(cartID)"];
    return $cartID + 1;
}

function createStatus($conn) {
    $query = "INSERT INTO statuses(status_message) VALUES ('pending')";
    mysqli_query($conn, $query);

    return mysqli_insert_id($conn);
}

function createCart($clientID, $cartArray, $cartID, $conn) {
    foreach ($cartArray as $productID => $quantity) {
        $query = "INSERT INTO carts(cartID, clientID, productID, quantity) VALUES ($cartID, $clientID, $productID, $quantity)";
        mysqli_query($conn, $query);
    }
}

function createOrder($clientID, $cartID, $statusID, $addressID, $contactID, $conn) {
    $query = "INSERT INTO orders(clientID, cartID, statusID, addressID, contactID) VALUES($clientID, $cartID, $statusID, $addressID, $contactID)";

    mysqli_query($conn, $query);
}

function listUserOrders($clientID, $conn) {
    $query = "SELECT * FROM orders WHERE clientID = ?";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }
    mysqli_stmt_bind_param($statement, "i", $clientID);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);

    $i = 1;
    while($row = mysqli_fetch_assoc($resultData)) {
        $addressID = $row["addressID"];
        $contactID = $row["contactID"];
        $statusID = $row["statusID"];
        $cartID = $row["cartID"];

        $addressQuery = "SELECT * FROM addresses WHERE addressID = $addressID";
        $addressResult = mysqli_query($conn, $addressQuery);
        $addressRow = mysqli_fetch_assoc($addressResult);

        $contactQuery = "SELECT * FROM contacts WHERE contactID = $contactID";
        $contactResult = mysqli_query($conn, $contactQuery);
        $contactRow = mysqli_fetch_assoc($contactResult);

        $statusQuery = "SELECT * FROM statuses WHERE statusID = $statusID";
        $statusResult = mysqli_query($conn, $statusQuery);
        $statusRow = mysqli_fetch_assoc($statusResult);

        $cartQuery = "SELECT * FROM carts WHERE cartID = $cartID";
        $cartResult = mysqli_query($conn, $cartQuery);

        echo '<div class="singleOrderWrapper">';
            echo '<div style="padding-top:10px;">';
                echo "<h2 style='border-bottom:1px solid #ddd; padding-bottom:10px;'>Order nr.$i</h2>";
            echo '</div>';
            echo '<div style="width:49%; float:left">';
                echo '<div>';
                    echo '<h2>Address<br></h2>';
                    $city = $addressRow["city"];
                    $street = $addressRow["street"];
                    $house_number = $addressRow["house_number"];
                    $postal_code = $addressRow["postal_code"];
                    $country = $addressRow["country"];

                    echo '<div class="addressDetails">';
                        echo '<p>Country: '.$country.'</p>';
                        echo '<p>City: '.$city.'</p>';
                        echo '<p>Postal code: '.$postal_code.'</p>';
                        echo '<p>Street: '.$street.'</p>';
                        echo '<p>House number: '.$house_number.'</p><br>';
                    echo '</div>';
                echo '</div>';

                echo '<div>';
                    echo '<h2>Contact<br></h2>';
                    $email = $contactRow["email"];
                    $phone_number = $contactRow["phone_number"];
                    $contactID = $contactRow["contactID"];

                    echo '<div>';
                        echo '<div>';
                            echo '<p>Email: '.$email.'</p>';
                            echo '<p>Phone number: '.$phone_number.'</p><br>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div>';
                    echo '<h2>Status</h2>';
                    $statusMessage = $statusRow["status_message"];

                    echo '<div>';
                        echo '<p>Order: '.$statusMessage.'</p><br><br>';
                    echo '</div>';
                echo '</div>';
                echo '<div style="clear:both;"></div>';
            echo '</div>';
            echo '<h2 style="padding-bottom:10px;">Products Ordered</h2>';
            echo '<div style="width:45%; float:right; padding:10px;">';
                $totalPrice = 0;
                while ($cartRow = mysqli_fetch_assoc($cartResult)) {
                    $productID = $cartRow["productID"];
                    $quantity = $cartRow["quantity"];

                    $productRow = getProductRow($productID, $conn);

                    $price = $productRow["price"];
                    $productName = $productRow["name"];
                    $path_to_img = $productRow["path_to_img"];
                    $totalPrice += ($price*$quantity);
                    echo '<div style="border-bottom: 1px solid #ddd;">';
                        echo '<img style="width:100px; height:auto;" alt="icon" src="'.$path_to_img.'"></img>';
                        echo '<div style="float:right;">';
                            echo "<p style='width:200px;'>$productName</p>";
                            echo "<p style='width:200px;'>Quantity: $quantity</p>";
                            echo "<p style='width:200px;'>Price: $price PLN </p>";
                        echo '</div>';
                        echo '<div style="clear:both;"></div>';
                    echo '</div>';

                }
            echo '</div>';
            echo '<div style="clear:both;"></div>';
        echo '</div>';
        $i++;
    }
}

function isAdmin($clientID, $conn) {
    $query = "SELECT * FROM clients WHERE clientID = $clientID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row["isAdmin"] == 1) return true;
    else return false;
}

function getCategories($conn) {
    $query = "SELECT * FROM categories";
    $result = mysqli_query($conn, $query);
    $categories = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($categories, $row["category_name"]);
    }
    return $categories;
}

function getCategoryID($category, $conn) {
    $query = "SELECT * FROM categories";

    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    mysqli_stmt_close($statement);

    $categoryID;
    while ($row = mysqli_fetch_assoc($resultData)) {
        if ($row["category_name"] === $category) {
            $categoryID = $row["categoryID"];
        }
    }
    return $categoryID;
}

function addProduct($name, $price, $path, $description, $category, $conn) {
    $categoryID = getCategoryID($category, $conn);
    $query = "INSERT INTO products(categoryID, name, price, path_to_img, description)
              VALUES (?, ?, ?, ?, ?)";

    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "isdss", $categoryID, $name, $price, $path, $description);
    $return = mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    return $return;
}

function updateStatus($statusID, $newStatus, $conn) {
    $query = "UPDATE statuses SET status_message = ? WHERE statusID = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $query)) {
        return false;
    }

    mysqli_stmt_bind_param($statement, "si", $newStatus, $statusID);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}
