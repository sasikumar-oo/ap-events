<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once 'config/database.php';
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Update last login time
                            $update_sql = "UPDATE admin_users SET last_login = NOW() WHERE id = ?";
                            if($update_stmt = $conn->prepare($update_sql)){
                                $update_stmt->bind_param("i", $id);
                                $update_stmt->execute();
                                $update_stmt->close();
                            }
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #800020;
            --primary-hover: #5a0017;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f1f1f1;
            --border-color: #ddd;
            --error-color: #dc3545;
            --success-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .login-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .login-header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            height: 50px;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(128, 0, 32, 0.25);
        }
        
        .form-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: var(--primary-hover);
        }
        
        .login-footer {
            text-align: center;
            padding: 15px;
            background-color: var(--secondary-color);
            font-size: 0.85rem;
            color: #666;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .error-feedback {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .alert {
            border-radius: 5px;
            margin-bottom: 1.5rem;
            padding: 0.75rem 1.25rem;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.2);
            color: var(--error-color);
        }
        
        .input-group-text {
            background-color: var(--light-gray);
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: 5px 0 0 5px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 5px 5px 0;
        }
        
        .toggle-password {
            cursor: pointer;
            color: #666;
            padding: 0 15px;
            background: var(--light-gray);
            border: 1px solid var(--border-color);
            border-left: none;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .toggle-password:hover {
            background-color: #e9ecef;
        }
        
        .logo {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .logo img {
            max-width: 120px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <h1><i class="fas fa-user-shield me-2"></i>Admin Panel</h1>
            </div>
            <p>Sign in to start your session</p>
        </div>
        
        <div class="login-body">
            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-floating mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" 
                               value="<?php echo $username; ?>" placeholder="Username" id="username">
                    </div>
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                
                <div class="form-floating mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                               placeholder="Password" id="password">
                        <span class="toggle-password" toggle="#password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="forgot-password.php" class="text-decoration-none">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn btn-login mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Don't have an account? <a href="register.php" class="text-decoration-none">Contact Administrator</a></p>
                </div>
            </form>
        </div>
        
        <div class="login-footer">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Your Business Name. All rights reserved.</p>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle password visibility
        $(".toggle-password").click(function() {
            $(this).toggleClass("active");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr("type", "password");
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
        // Focus on username field on page load
        $(document).ready(function(){
            $("#username").focus();
        });
    </script>
</body>
</html>
