<?php
function validateSignupForm($data) {
    $errors = [];
    
    // Validate first name
    if (empty($data['first_name'])) {
        $errors['first_name'] = 'First name is required';
    } elseif (strlen($data['first_name']) < 2) {
        $errors['first_name'] = 'First name must be at least 2 characters';
    }
    
    // Validate last name
    if (empty($data['last_name'])) {
        $errors['last_name'] = 'Last name is required';
    } elseif (strlen($data['last_name']) < 2) {
        $errors['last_name'] = 'Last name must be at least 2 characters';
    }
    
    // Validate username
    if (empty($data['username'])) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($data['username']) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
        $errors['username'] = 'Username can only contain letters, numbers, and underscores';
    }
    
    // Validate email
    if (empty($data['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    // Validate password
    if (empty($data['password'])) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($data['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
    }
    
    // Validate confirm password
    if (empty($data['confirm_password'])) {
        $errors['confirm_password'] = 'Please confirm your password';
    } elseif ($data['password'] !== $data['confirm_password']) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    return $errors;
}

function checkUserExists($conn, $username, $email) {
    $query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}
?>