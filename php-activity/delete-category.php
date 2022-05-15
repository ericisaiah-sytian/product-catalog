<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: category-backend.php");
    exit;
}

// Include database file
require 'functions/class.Database.inc';

$database = new Database;

$db = Database::getInstance();
$mysqli = $db->getConnection();

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $userid = $_POST["id-of-user"];
    $categoryname = $_POST["category_name"];

    $sql = "DELETE FROM categories WHERE id=$userid";

    $sqlCheck = "SELECT id FROM products WHERE categoryname = ?";

    if($stmt = $mysqli->prepare($sqlCheck)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_categoryname);
        
        // Set parameters
        $param_categoryname = $categoryname;
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // store result
            $stmt->store_result();
            
            if($stmt->num_rows >= 1){
                $message = "Cannot delete, this category is already in used by one or more products.";
            } else{
                if ($mysqli->query($sql) === TRUE) {
                    $message = "Category sucessfully deleted.";
                  } else {
                    $message = "Error deleting the category. Please try again.";
                }
            }
        } else{
            $message = "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        $stmt->close();
    }
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
    <?php
        require 'header-backend.php'; ?>

        <section class = "py-5">
            <div class = "container text-center">
                <p><?php echo $message; ?></p>
                <div class = "add-to-db-wrapper">
                    <a class = "add-to-db-btn" href = "category-backend.php">Go Back</a>
                </div>
            </div>
        </section>
</body>