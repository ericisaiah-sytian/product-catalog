<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to index page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: backend.php");
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

    $sql = "DELETE FROM products WHERE id=$userid";

    if ($mysqli->query($sql) === TRUE) {
        $message = "Product sucessfully deleted.";
      } else {
        $message = "Error deleting the product. Please try again.";
      }
}
else {
    header("location: backend.php");
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
                    <a class = "add-to-db-btn" href = "backend.php">Go Back</a>
                </div>
            </div>
        </section>
</body>