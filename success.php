<?php
require_once('includes/db.php');
require_once('includes/functions.php');

if (!isset($_GET['email']) || !isset($_GET['product_id'])) {
    redirect('index.php');
}

$email = sanitize($_GET['email']);
$product_id = intval($_GET['product_id']);

// Get product details
$product = mysqli_query($conn, "SELECT title FROM products WHERE id = $product_id");
if (mysqli_num_rows($product) == 0) redirect('index.php');
$product = mysqli_fetch_assoc($product);

$page_title = "Order Received | DigitalStore";
require_once('includes/header.php'); 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    <h1 class="mb-3">Thank You For Your Order!</h1>
                    <p class="lead">We've received your payment for <strong><?= htmlspecialchars($product['title']) ?></strong></p>
                    
                    <div class="card border-0 bg-light my-4">
                        <div class="card-body">
                            <h5 class="card-title">What Happens Next?</h5>
                            <div class="d-flex flex-column gap-3 mt-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="mb-1">Payment Verification</h6>
                                        <p class="mb-0 text-muted">Our team will verify your payment within 1-2 hours</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-envelope fa-lg"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="mb-1">Account Setup</h6>
                                        <p class="mb-0 text-muted">We'll email your login details to <strong><?= htmlspecialchars($email) ?></strong></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="fas fa-download fa-lg"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="mb-1">Instant Access</h6>
                                        <p class="mb-0 text-muted">Download your files immediately after verification</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Need help? <a href="contact.php" class="alert-link">Contact our support team</a>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mt-5">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i> Back to Home
                        </a>
                        <a href="contact.php" class="btn btn-primary">
                            <i class="fas fa-headset me-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>