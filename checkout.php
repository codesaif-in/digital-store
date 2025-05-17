<?php
require_once('includes/db.php');
require_once('includes/functions.php');

// Debugging - remove in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    die("Product ID missing");
    redirect('index.php');
}

$product_id = intval($_GET['id']);
$product = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
if (mysqli_num_rows($product) == 0) {
    die("Product not found");
    redirect('index.php');
}
$product = mysqli_fetch_assoc($product);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $screenshot = $_FILES['screenshot'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please fill all fields correctly!";
    } else {
        // Process file upload
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                die("Failed to create upload directory");
            }
        }
        
        $filename = time() . '_' . basename($screenshot['name']);
        $target_file = $upload_dir . $filename;
        
        // Debug file upload
        echo "<pre>"; print_r($screenshot); echo "</pre>";
        
        if (move_uploaded_file($screenshot['tmp_name'], $target_file)) {
            // Save order with error handling
            $query = "INSERT INTO orders (product_id, name, email, phone, payment_screenshot) 
                     VALUES ($product_id, '$name', '$email', '$phone', '$filename')";
            
            if (mysqli_query($conn, $query)) {
                // Debug before redirect
                echo "Redirecting to success page...";
                redirect('success.php?email=' . urlencode($email) . '&product_id=' . $product_id);
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "File upload failed. Error: " . $screenshot['error'];
        }
    }
}

// Rest of your HTML form...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | DigitalStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .checkout-steps {
            display: flex;
            margin-bottom: 2rem;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            position: relative;
        }
        .step-number {
            width: 30px;
            height: 30px;
            background: #e9ecef;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }
        .step.active .step-number {
            background: var(--bs-primary);
            color: white;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            right: -50%;
            height: 2px;
            background: #e9ecef;
            z-index: -1;
        }
        .payment-methods img {
            height: 30px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .order-summary {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include('includes/header.php'); ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <!-- Checkout Steps -->
                <div class="checkout-steps">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-title">Payment</div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-title">Verification</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-title">Download</div>
                    </div>
                </div>

                <h2 class="mb-4">Complete Your Purchase</h2>

                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-rupee-sign me-2"></i> Payment Details
                        </h4>

                        <div class="payment-methods mb-4">
                            <h5>Step 1: Send Payment via UPI</h5>
                            <div class="d-flex flex-wrap align-items-center mb-3">
                                <img src="https://via.placeholder.com/100x50?text=UPI" alt="UPI">
                                <img src="https://via.placeholder.com/100x50?text=GPay" alt="Google Pay">
                                <img src="https://via.placeholder.com/100x50?text=Paytm" alt="Paytm">
                                <img src="https://via.placeholder.com/100x50?text=PhonePe" alt="PhonePe">
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="yourstore@upi" id="upiId" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyUpiId()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <div class="text-center mb-4">
                                <img src="https://via.placeholder.com/200x200?text=UPI+QR+Code" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                <p class="text-muted mt-2">Scan this QR code to pay instantly</p>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mt-4 mb-3">Step 2: Submit Payment Proof</h5>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Screenshot</label>
                                <input type="file" name="screenshot" class="form-control" accept="image/*" required>
                                <small class="text-muted">Upload clear screenshot of successful payment</small>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    I agree to the <a href="#">Terms & Conditions</a>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i> Submit Payment Proof
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-lock me-2"></i> Secure Payment</h5>
                        <p class="card-text">Your information is protected by 256-bit SSL encryption. We never store your payment details.</p>
                        <div class="d-flex flex-wrap">
                            <img src="https://via.placeholder.com/80x50?text=SSL" alt="SSL Secure" class="me-2 mb-2">
                            <img src="https://via.placeholder.com/80x50?text=PCI" alt="PCI Compliant" class="me-2 mb-2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card order-summary">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Order Summary</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Product:</span>
                            <span><?= htmlspecialchars($product['title']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Price:</span>
                            <span>₹<?= number_format($product['price'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax:</span>
                            <span>₹0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span class="text-success">₹<?= number_format($product['price'], 2) ?></span>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">What Happens Next?</h5>
                        <div class="mb-3">
                            <div class="d-flex mb-2">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">1. Payment Verification</h6>
                                    <small class="text-muted">Usually within 1-2 hours</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">2. Account Creation</h6>
                                    <small class="text-muted">We'll email your login details</small>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">3. Instant Access</h6>
                                    <small class="text-muted">Download your files immediately</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Need help? <a href="contact.php">Contact our support team</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyUpiId() {
            const upiId = document.getElementById('upiId');
            upiId.select();
            document.execCommand('copy');
            
            // Show tooltip or alert
            alert('UPI ID copied to clipboard: ' + upiId.value);
        }
    </script>
</body>
</html>