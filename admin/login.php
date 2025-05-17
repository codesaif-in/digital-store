<?php
session_start();
require_once('../includes/db.php');
require_once('../includes/functions.php');

// Hardcoded admin credentials (for testing)
$admin_email = "admin@digitalstore.com";
$admin_password = "admin123"; // In production, use password_hash()

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    
    // Debug output (remove in production)
    echo "<pre>Posted data:\n";
    print_r($_POST);
    echo "\nSession before:\n";
    print_r($_SESSION);
    echo "</pre>";
    
    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        
        // Debug
        echo "<pre>Session after login:\n";
        print_r($_SESSION);
        echo "</pre>";
        
        // Redirect to dashboard
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}

// If we reach here, either not POST or login failed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | DigitalStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-container">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Admin Login</h2>
                        
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="post" action="login.php">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required 
                                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>