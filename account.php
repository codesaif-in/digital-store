<?php
require_once('includes/db.php');
require_once('includes/functions.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'account.php';
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user);

// Get user's orders
$orders = mysqli_query($conn, "SELECT o.*, p.title, p.price 
                              FROM orders o 
                              JOIN products p ON o.product_id = p.id 
                              WHERE o.user_id = $user_id
                              ORDER BY o.created_at DESC");

$page_title = "My Account | DigitalStore";
require_once('includes/header.php'); 
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar mb-3" style="width: 100px; height: 100px; background: #f0f0f0; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <h4><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="account.php" class="text-decoration-none"><i class="fas fa-user me-2"></i> My Account</a>
                    </li>
                    <li class="list-group-item">
                        <a href="orders.php" class="text-decoration-none"><i class="fas fa-shopping-bag me-2"></i> My Orders</a>
                    </li>
                    <li class="list-group-item">
                        <a href="downloads.php" class="text-decoration-none"><i class="fas fa-download me-2"></i> Downloads</a>
                    </li>
                    <li class="list-group-item">
                        <a href="logout.php" class="text-decoration-none text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Account Overview</h3>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Personal Info</h5>
                                    <hr>
                                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                                    <p><strong>Phone:</strong> <?= $user['phone'] ? htmlspecialchars($user['phone']) : 'Not provided' ?></p>
                                    <a href="edit-profile.php" class="btn btn-sm btn-outline-primary mt-2">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-lock me-2"></i>Security</h5>
                                    <hr>
                                    <p><strong>Password:</strong> ********</p>
                                    <a href="change-password.php" class="btn btn-sm btn-outline-primary">Change Password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mt-4 mb-3">Recent Orders</h4>
                    
                    <?php if (mysqli_num_rows($orders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['title']) ?></td>
                                        <td>₹<?= number_format($order['price'], 2) ?></td>
                                        <td>
                                            <?php if ($order['is_verified']): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Processing</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <a href="order-details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="orders.php" class="btn btn-outline-primary">View All Orders</a>
                    <?php else: ?>
                        <div class="alert alert-info">
                            You haven't placed any orders yet. <a href="index.php">Browse products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>