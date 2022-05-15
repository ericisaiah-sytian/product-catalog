<?php 
    session_start();

    // Include database file
    require 'functions/class.Database.inc';

    $database = new Database;

    $db = Database::getInstance();
    $mysqli = $db->getConnection();
     
    // Define variables and initialize with empty values
    $categoryname_err = "";
     
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        // Validate category name
        if(empty(trim($_POST["category-name"]))){
            $categoryname_err = "Please enter a category name.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM categories WHERE name = ?";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_categoryname);
                
                // Set parameters
                $param_categoryname = trim($_POST["category-name"]);
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // store result
                    $stmt->store_result();
                    
                    if($stmt->num_rows >= 1){
                        $categoryname_err = "This category name is already taken.";
                    } else{
                        $categoryname = trim($_POST["category-name"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
        
        // Check input errors before inserting in database
        if(empty($categoryname_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO categories (name) VALUES (?)";
             
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_categoryname);
                
                // Set parameters
                $param_categoryname = $categoryname;

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Redirect to login page
                    header("location: category-backend.php");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
        
        // Close connection
        $mysqli->close();
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
                            <h2>Add Category</h2>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="category-name" class="form-control <?php echo (!empty($categoryname_err)) ? 'is-invalid' : ''; ?>" value="">
                                <span class="invalid-feedback"><?php echo $categoryname_err; ?></span>
                            </div>    
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