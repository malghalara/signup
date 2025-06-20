<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/validation.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirectTo('dashboard.php');
}

$errors = [];
$success = '';
$formData = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $formData = [
        'first_name' => sanitizeInput($_POST['first_name']),
        'last_name' => sanitizeInput($_POST['last_name']),
        'username' => sanitizeInput($_POST['username']),
        'email' => sanitizeInput($_POST['email']),
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password']
    ];
    
    // Validate form data
    $errors = validateSignupForm($formData);
    
    if (empty($errors)) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            // Check if user already exists
            if (checkUserExists($conn, $formData['username'], $formData['email'])) {
                $errors['general'] = 'Username or email already exists!!!!';
            } else {
                // Insert new user
                $query = "INSERT INTO users (first_name, last_name, username, email, password) 
                         VALUES (:first_name, :last_name, :username, :email, :password)";
                
                $stmt = $conn->prepare($query);
                $hashedPassword = hashPassword($formData['password']);
                
                $stmt->bindParam(':first_name', $formData['first_name']);
                $stmt->bindParam(':last_name', $formData['last_name']);
                $stmt->bindParam(':username', $formData['username']);
                $stmt->bindParam(':email', $formData['email']);
                $stmt->bindParam(':password', $hashedPassword);
                
                if ($stmt->execute()) {
                    $success = 'Account created successfully! You can now <a href="login.php">login</a>.';
                    $formData = []; // Clear form data
                } else {
                    $errors['general'] = 'Registration failed. Please try again.';
                }
            }
        } catch (PDOException $e) {
            $errors['general'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2 class="form-title">Create Account</h2>
        
        <?php if (!empty($errors['general'])): ?>
            <?php echo showAlert($errors['general'], 'danger'); ?>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <?php echo showAlert($success, 'success'); ?>
        <?php endif; ?>
        
        <form id="signupForm" method="POST" action="">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" 
                       value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>" required>
                <?php if (!empty($errors['first_name'])): ?>
                    <div class="error-message"><?php echo $errors['first_name']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" 
                       value="<?php echo htmlspecialchars($formData['last_name'] ?? ''); ?>" required>
                <?php if (!empty($errors['last_name'])): ?>
                    <div class="error-message"><?php echo $errors['last_name']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                <?php if (!empty($errors['username'])): ?>
                    <div class="error-message"><?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <div class="error-message"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <?php if (!empty($errors['password'])): ?>
                    <div class="error-message"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                <?php if (!empty($errors['confirm_password'])): ?>
                    <div class="error-message"><?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn">Create Account</button>
        </form>
        
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    
    <script src="js/validation.js"></script>
</body>
</html>