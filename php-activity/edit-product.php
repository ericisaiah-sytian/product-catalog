<?php 
    session_start();

    // Include database file
    require 'functions/class.Database.inc';

    $database = new Database;

    $db = Database::getInstance();
    $mysqli = $db->getConnection();
     
    // Define variables and initialize with empty values
    $productname_err = "";
     
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $approved_changed = $_POST['approved_changed'];
        $categoryname = $_POST["category_name"];
        $productname = $_POST["product_name"];
        $brandname = $_POST["brand_name"];
        $productprice = $_POST["product_price"];
        $productimagevalidator = $_POST['image_validator'];

        if($productimagevalidator == 'sid83j1xzl28') {
            $productimage = 'assets/images/' . $_POST['image_validator'];
        }
        else {
            $productimage = 'assets/images/' . $_FILES['image']['name'];
        }
        

        // define variables
        $initial_category = $_POST["category_name"];
        $initial_categoryname = $_POST["category_name"];
        $initial_productname = $_POST["product_name"];
        $initial_brandname = $_POST["brand_name"];
        $initial_productprice = $_POST["product_price"];
        $userid = $_POST["id-of-user"];

     
        // Validate product name
        if(empty(trim($_POST["product_name"])) || $productimagevalidator == 'sid83j1xzl28'){
            $productname_err = "";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM products WHERE productname = ? AND id != $userid";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_productname);
                
                // Set parameters
                $param_productname = $_POST["product_name"];

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // store result
                    $stmt->store_result();
                    
                    if($stmt->num_rows >= 1){
                        $productname_err = "This product name is already taken.";
                    } else{
                        // saving data to database
                        $productname = $_POST["product_name"];
                        $categoryname = $_POST["category_name"];
                        $brandname = $_POST["brand_name"];
                        $productprice = $_POST["product_price"];
                        $productimage = 'assets/images/' . $_FILES['image']['name'];

                        // uploading image to folder
                        $tmpname = $_FILES['image']['tmp_name'];
                        $img = $_FILES['image']['name'];

                        if (move_uploaded_file($tmpname, __DIR__.'/assets/images/'. $img)) {
                            // file uploaded
                        } else {
                            // file not uploaded
                        }
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
        
        // Check input errors before inserting in database
        if(empty($productname_err) && empty($approved_changed)){
            // Prepare an insert statement
            $sql = "UPDATE products SET productname = '$productname', categoryname = '$categoryname', brandname = '$brandname', productprice = '$productprice', productimage = '$productimage' WHERE id = '$userid';";

            // Check if Product already exists
            if ($mysqli->query($sql) === TRUE) {
                header("location: backend.php");
            } else {
                $category_err = "Product already exists.";
            }
            
        }
        
        // Close connection
        // $mysqli->close();
    }
    
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

    <?php require 'header-backend.php'; 

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        // if not logged in.
        header("location: index.php");
        exit;
    }

    else {
        // if logged in.
        ?>
        <div class="add-user-wrapper">
            <div class = "container h-100">
                <div class = "row justify-content-center align-items-center h-100">
                    <div class = "col-12 col-md-6 col-lg-4">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <h2>Add Product</h2>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" class="form-control <?php echo (!empty($productname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $initial_productname; ?>">
                                <span class="invalid-feedback"><?php echo $productname_err; ?></span>
                            </div>
                            <div class = "form-group">
                                <label>Product Image</label>
                                <input type="file" name="image" required>
                                <input type="hidden" name="image_validator" value="">
                            </div>
                            <div class = "form-group">
                                <label>Category</label>
                                <select name="category_name" class="form-control" value="<?php echo $initial_categoryname; ?>" required>
                                     <?php 
                                        $selectcat = "SELECT id, name FROM categories";
                                        $resultcat = $mysqli->query($selectcat);
                                    ?>
                                        <?php 
                                        if ($resultcat->num_rows > 0) {
                                            
                                            // output data of each row
                                            while($row = $resultcat->fetch_assoc()) {
                                            echo "<option value='". $row['name']."'>". $row['name']."</option>";
                                            }
                                        } else {
                                            echo "";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class = "form-group">
                                <label>Brand</label>
                                <select name="brand_name" class="form-control" value="<?php echo $initial_brandname; ?>" required>
                                     <?php 
                                        $selectbrand = "SELECT id, brandname FROM brands";
                                        $resultbrand = $mysqli->query($selectbrand);
                                    ?>
                                        <?php 
                                        if ($resultbrand->num_rows > 0) {
                                            
                                            // output data of each row
                                            while($row = $resultbrand->fetch_assoc()) {
                                            echo "<option value='". $row['brandname']."'>". $row['brandname']."</option>";
                                            }
                                        } else {
                                            echo "";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="number" name="product_price" class="form-control" value="<?php echo $initial_productprice; ?>" required>
                            </div>
                            <input type="hidden" name="id-of-user" value="<?php echo $userid; ?>">
                            <input type="hidden" name="approved_changed" value="">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
        <?php
    }

    ?>

    <section>
        <div>

        </div>
    </section>
</body>