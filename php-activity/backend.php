<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Include database file
require 'functions/class.Database.inc';

$database = new Database;

$db = Database::getInstance();
$mysqli = $db->getConnection();

?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <title>PHP Activity - Sytian</title>
    <meta name="description" content="PHP Activity for Sytian Productions" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--CSS and JS -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/css/slick.css">
    <link type="text/css" rel="stylesheet" href="assets/plugins/css/aos.css">
    <link type="text/css" rel="stylesheet" href="assets/plugins/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="assets/css/main.css">
    
    <script src="assets/plugins/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/js/bootstrap.min.js"></script>
    <script src="assets/plugins/js/slick.min.js"></script>
    <script src="assets/plugins/js/aos.js"></script>
    <script src="assets/js/main.js"></script>
</head>

<body>
    <?php
        require 'header-backend.php'; ?>

    <div class="container">
        <div class = "add-to-db-wrapper text-center text-lg-right">
            <a class = "add-to-db-btn" href = "add-product.php">Add Product</a>
        </div>

        <div class = "content-wrapper">
            <div class = "row justify-content-center align-items-center">
                <table class = "backend-table">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                        <?php 
                        $selectproducts = "SELECT id, productname, productimage, categoryname, brandname, productprice FROM products";
                        $resultproducts = $mysqli->query($selectproducts);
                    ?>
                        <?php 
                        if ($resultproducts->num_rows > 0) {
                            
                            // output data of each row
                            while($row = $resultproducts->fetch_assoc()) {
                            ?>
                                <tr id = "user-id-<?php echo $row["id"]; ?>">
                                <!-- Content Loop Starts Here -->
                                    <form action="edit-product.php" method="post">
                                    <input type="hidden" name="id-of-user" value="<?php echo $row["id"]; ?>">
                                    <td><input type="text" name = "product_name" value = "<?php echo $row["productname"]; ?>"></td>
                                    <td class = "product-img-td"><img src = "<?php echo $row["productimage"]; ?>" alt = "<?php echo $row["productname"]; ?>"></td>
                                    <td><input type="text" name = "category_name" value = "<?php echo $row["categoryname"]; ?>"></td>
                                    <td><input type="text" name = "brand_name" value = "<?php echo $row["brandname"]; ?>"></td>
                                    <td><input type="text" name = "product_price" value = "<?php echo $row["productprice"]; ?>"></td>
                                    <input type="hidden" name="image_validator" value="sid83j1xzl28">
                                    <input type="hidden" name="approved_changed" value="yes">
                                    <td>
                                        <input class = "edit-a mr-2" type="submit" value="Edit">
                                    </form>
                                    <form action="delete-product.php" method="post">
                                        <input type="hidden" name="id-of-user" value="<?php echo $row["id"]; ?>">
                                        <input class = "delete-a " type="submit" value="Delete">
                                    </form>
                                    </td>
                                </tr>
                            
                            <?php
                            }
                        } else {
                            echo "";
                        }
                    ?>
                    <!-- Content Loop Ends Here -->
                </table>
            </div>
        </div>
    </div>


    
    
    
</body>