<?php 
    session_start();

    // Include database file
    require 'functions/class.Database.inc';

    $database = new Database;

    $db = Database::getInstance();
    $mysqli = $db->getConnection();
     
    // Define variables and initialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
     
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
     
        // Validate username
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } else {
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_username);
                
                // Set parameters
                $param_username = trim($_POST["username"]);
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // store result
                    $stmt->store_result();
                    
                    if($stmt->num_rows >= 1){
                        $username_err = "This username is already taken.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
        
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
        
        // Check input errors before inserting in database
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
             
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $param_username, $param_password);
                
                // Set parameters
                $param_username = $username;
                $param_password = $password;
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Redirect to login page
                    header("location: users.php");
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
                            <h2>Add User</h2>
                            <p>Please fill this form to create an account.</p>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>    
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
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