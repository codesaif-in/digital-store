<?php
require_once('includes/db.php');
require_once('includes/functions.php');

if (!isset($_GET['email']) || !isset($_GET['product_id'])) {
    redirect('index.php');
}

$email = sanitize($_GET['email']);
$product_id = intval($_GET['product_id']);

// Check if order is verified
$order = mysqli_query($conn, "
    SELECT p.file_url, p.title 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.email = '$email' 
    AND o.product_id = $product_id
    AND o.is_verified = 1
    LIMIT 1
");

if (mysqli_num_rows($order) == 0) {
    $page_title = "Access Denied | DigitalStore";
    require_once('includes/header.php');
    ?>
    <div class="container my-5">
        <div class="alert alert-danger text-center">
            <h2><i class="fas fa-lock me-2"></i> Access Restricted</h2>
            <p class="lead mt-3">Your order is still under verification or doesn't exist.</p>
            <p>We'll notify you at <strong><?= htmlspecialchars($email) ?></strong> once verified.</p>
            <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    </div>
    <?php
    require_once('includes/footer.php');
    exit();
}

$order = mysqli_fetch_assoc($order);
$file_url = $order['file_url'];
$file_name = basename($file_url);

// Log download

// After successful download
mysqli_query($conn, "INSERT INTO downloads (user_id, product_id, order_id) 
                     VALUES ($user_id, $product_id, $order_id)");
                     
mysqli_query($conn, "
    UPDATE orders SET download_count = download_count + 1 
    WHERE email = '$email' AND product_id = $product_id
");

$page_title = "Download | " . htmlspecialchars($order['title']);
require_once('includes/header.php'); 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-download fa-5x text-primary"></i>
                    </div>
                    <h1 class="mb-3">Download Your File</h1>
                    <p class="lead">Thank you for purchasing <strong><?= htmlspecialchars($order['title']) ?></strong></p>
                    
                    <div class="card border-0 bg-light my-4">
                        <div class="card-body text-start">
                            <h5 class="card-title mb-3">Download Instructions</h5>
                            <ol>
                                <li class="mb-2">Click the download button below</li>
                                <li class="mb-2">Save the file to your computer</li>
                                <li>If you encounter issues, contact our support</li>
                            </ol>
                        </div>
                    </div>
                    
                    <a href="<?= $file_url ?>" class="btn btn-primary btn-lg mt-3" download>
                        <i class="fas fa-download me-2"></i> Download Now
                    </a>
                    
                    <div class="alert alert-warning mt-4 text-start">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i> Important</h5>
                        <ul class="mb-0">
                            <li>This download link will expire in 7 days</li>
                            <li>You can always access your files from <a href="account.php">your account</a></li>
                            <li>Maximum 3 download attempts allowed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>