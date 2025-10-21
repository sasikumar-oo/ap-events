<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root'; // Default XAMPP username
$db_pass = '';     // Default XAMPP password
$db_name = 'business_cms';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db_name);

// Create admin_users table
$sql = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Admin users table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create pages table
$sql = "CREATE TABLE IF NOT EXISTS pages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    status ENUM('published', 'draft') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Pages table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create services table
$sql = "CREATE TABLE IF NOT EXISTS services (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(100),
    image VARCHAR(255),
    display_order INT(11) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Services table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create gallery table
$sql = "CREATE TABLE IF NOT EXISTS gallery (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    display_order INT(11) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Gallery table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create testimonials table
$sql = "CREATE TABLE IF NOT EXISTS testimonials (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_position VARCHAR(100),
    company VARCHAR(100),
    content TEXT NOT NULL,
    image VARCHAR(255),
    rating TINYINT(1) DEFAULT 5,
    status ENUM('published', 'pending', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Testimonials table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create settings table
$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Settings table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Insert default admin user (username: admin, password: admin123)
$hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO admin_users (username, password, email, full_name) 
        VALUES ('admin', '$hashed_password', 'admin@example.com', 'Administrator')";

if ($conn->query($sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        echo "<br>Default admin user created successfully<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<strong>IMPORTANT: Change the default password after first login!</strong><br>";
    } else {
        echo "Admin user already exists<br>";
    }
} else {
    echo "Error creating admin user: " . $conn->error . "<br>";
}

// Insert default settings
$default_settings = [
    ['site_title', 'My Business', 'general'],
    ['site_description', 'Professional Business Solutions', 'general'],
    ['contact_email', 'info@example.com', 'contact'],
    ['contact_phone', '+1 234 567 890', 'contact'],
    ['contact_address', '123 Business St, Your City', 'contact'],
    ['facebook_url', 'https://facebook.com', 'social'],
    ['twitter_url', 'https://twitter.com', 'social'],
    ['instagram_url', 'https://instagram.com', 'social'],
    ['linkedin_url', 'https://linkedin.com', 'social']
];

$stmt = $conn->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?)");

foreach ($default_settings as $setting) {
    $stmt->bind_param("sss", $setting[0], $setting[1], $setting[2]);
    $stmt->execute();
}

echo "Default settings added successfully<br>";

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/../uploads';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
    mkdir($upload_dir . '/gallery', 0755, true);
    mkdir($upload_dir . '/testimonials', 0755, true);
    mkdir($upload_dir . '/services', 0755, true);
    echo "Upload directories created successfully<br>";
}

// Create config file
$config_content = "<?php
// Database configuration
define('DB_HOST', '$db_host');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
define('DB_NAME', '$db_name');

// Site URL (update this with your actual domain)
define('SITE_URL', 'http://' . \$_SERVER['HTTP_HOST'] . '/admin');

define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Create database connection
\$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (\$conn->connect_error) {
    die("Connection failed: " . \$conn->connect_error);
}
";

file_put_contents(__DIR__ . '/config.php', $config_content);

// Close connection
$conn->close();

echo "<h2>Installation Complete!</h2>";
echo "<p>Admin panel has been successfully installed.</p>";
echo "<p><a href='login.php'>Go to Login Page</a></p>";
echo "<p><strong>Important:</strong> Delete or rename the install.php file for security reasons.</p>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Installation Complete</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .btn {
            display: inline-block;
            background: #800020;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #5a0017;
        }
    </style>
</head>
<body>
    <div class="success">
        <h2>Installation Complete!</h2>
        <p>Your admin panel has been successfully installed.</p>
        <p><strong>Important:</strong> For security reasons, please delete or rename the install.php file.</p>
    </div>
    
    <h3>Next Steps:</h3>
    <ol>
        <li>Login to the admin panel using the following credentials:
            <ul>
                <li>Username: admin</li>
                <li>Password: admin123</li>
            </ul>
        </li>
        <li>Change the default admin password immediately after logging in.</li>
        <li>Update your website settings in the admin panel.</li>
        <li>Start adding your content (pages, services, gallery items, etc.).</li>
    </ol>
    
    <a href="login.php" class="btn">Go to Admin Login</a>
</body>
</html>
