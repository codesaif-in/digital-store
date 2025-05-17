<?php
$page_title = "Order Management";
require_once('includes/admin_header.php');

// Handle order actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $order_id = intval($_GET['id']);
    
    if ($action == 'verify') {
        mysqli_query($conn, "UPDATE orders SET is_verified = 1 WHERE id = $order_id");
        $_SESSION['message'] = "Order #$order_id verified successfully";
        redirect('orders.php');
    } elseif ($action == 'delete') {
        mysqli_query($conn, "DELETE FROM orders WHERE id = $order_id");
        $_SESSION['message'] = "Order #$order_id deleted successfully";
        redirect('orders.php');
    } elseif ($action == 'view') {
        $order = mysqli_query($conn, "
            SELECT o.*, p.title as product_name, p.price, p.file_url 
            FROM orders o
            JOIN products p ON o.product_id = p.id
            WHERE o.id = $order_id
        ");
        $order = mysqli_fetch_assoc($order);
    }
}

// Filter orders
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = '';
if ($filter == 'pending') $where = "WHERE o.is_verified = 0";
if ($filter == 'completed') $where = "WHERE o.is_verified = 1";

$orders = mysqli_query($conn, "
    SELECT o.*, p.title as product_name, p.price 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    $where
    ORDER BY o.created_at DESC
");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Order Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="orders.php?filter=all" class="btn btn-sm <?= $filter == 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">All</a>
            <a href="orders.php?filter=pending" class="btn btn-sm <?= $filter == 'pending' ? 'btn-primary' : 'btn-outline-secondary' ?>">Pending</a>
            <a href="orders.php?filter=completed" class="btn btn-sm <?= $filter == 'completed' ? 'btn-primary' : 'btn-outline-secondary' ?>">Completed</a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?= $_SESSION['message'] ?></div>
<?php unset($_SESSION['message']); endif; ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'view'): ?>
<!-- Order Detail View -->
<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title">Order #<?= $order['id'] ?></h4>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Customer Details</h5>
                <p>
                    <strong>Name:</strong> <?= $order['name'] ?><br>
                    <strong>Email:</strong> <?= $order['email'] ?><br>
                    <strong>Phone:</strong> <?= $order['phone'] ?><br>
                    <strong>Order Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                </p>
            </div>
            <div class="col-md-6">
                <h5>Product Details</h5>
                <p>
                    <strong>Product:</strong> <?= $order['product_name'] ?><br>
                    <strong>Amount:</strong> ₹<?= number_format($order['price'], 2) ?><br>
                    <strong>Status:</strong> 
                    <?php if ($order['is_verified']): ?>
                        <span class="badge bg-success">Verified</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <h5>Payment Proof</h5>
        <img src="../../uploads/payments/<?= $order['payment_screenshot'] ?>" class="img-fluid mb-3" style="max-height: 300px;">
        
        <div class="d-flex gap-2">
            <?php if (!$order['is_verified']): ?>
                <a href="orders.php?action=verify&id=<?= $order['id'] ?>" class="btn btn-success">Verify Payment</a>
            <?php endif; ?>
            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Orders List -->
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= $order['product_name'] ?></td>
                <td><?= $order['name'] ?><br><small><?= $order['email'] ?></small></td>
                <td>₹<?= number_format($order['price'], 2) ?></td>
                <td>
                    <?php if ($order['is_verified']): ?>
                        <span class="badge bg-success">Verified</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="orders.php?action=view&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                        <?php if (!$order['is_verified']): ?>
                            <a href="orders.php?action=verify&id=<?= $order['id'] ?>" class="btn btn-sm btn-success">Verify</a>
                        <?php endif; ?>
                        <a href="orders.php?action=delete&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once('includes/admin_footer.php'); ?>