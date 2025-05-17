<?php
require_once('includes/db.php');
require_once('includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    
    $user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($user) == 1) {
        $user = mysqli_fetch_assoc($user);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to account page or previous URL
            $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'account.php';
            unset($_SESSION['redirect_url']);
            redirect($redirect);
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Account not found";
    }
}

$page_title = "Login | DigitalStore";
require_once('includes/header.php'); 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Login to Your Account</h2>
                    
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="forgot-password.php">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        Don't have an account? <a href="register.php">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>