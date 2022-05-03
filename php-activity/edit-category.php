<?php 
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
     
    // Define variables and initialize with empty values
    
    $category_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $approved_changed = $_POST['approved_changed'];
        $category_name = $_POST["category_name"];

        // define variables
        $initial_category = $_POST["category_name"];
        $userid = $_POST["id-of-user"];

     
        // Validate category name
        if(empty($_POST["category_name"])){
            $category_err = "Please enter a category name.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM categories WHERE name = ? AND id != $userid";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_category);
                
                // Set parameters
                $param_category = $_POST["category_name"];
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // store result
                    $stmt->store_result();
                    
                    if($stmt->num_rows >= 1){
                        $category_err = "This category name already exists.";
                    } else{
                        $category_name = $_POST["category_name"];
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
        
        
        // Check input errors before inserting in database
        if(empty($category_err) && empty($approved_changed)){

            // Prepare an update statement
            $sql = "UPDATE categories SET name = '$category_name' WHERE id = '$userid';";
            
            // Check if Username already exists
            if ($mysqli->query($sql) === TRUE) {
                header("location: category-backend.php");
            } else {
                $category_err = "Category already exists.";
            }
        }
        
        // Close connection
        $mysqli->close();
    }
    else {
        header("location: category-backend.php");
        exit;
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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <h2>Edit Category</h2>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="category_name" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $initial_category; ?>">
                                <span class="invalid-feedback"><?php echo $category_err; ?></span>
                                <input type="hidden" name="id-of-user" value="<?php echo $userid; ?>">
                                <input type="hidden" name="approved_changed" value="">
                            </div>    
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
        <?php
    }

    ?>
</body>