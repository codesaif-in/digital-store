<?php
require_once('includes/db.php');
require_once('includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);
    $phone = sanitize($_POST['phone'] ?? '');

    // Check if email exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        // Create account
        $result = mysqli_query($conn, "INSERT INTO users (name, email, password, phone) 
                                     VALUES ('$name', '$email', '$password', '$phone')");
        
        if ($result) {
            // Auto-login
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            redirect('account.php');
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

$page_title = "Register | DigitalStore";
require_once('includes/header.php'); 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Create Account</h2>
                    
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="terms.php">Terms & Conditions</a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        Already have an account? <a href="login.php">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>