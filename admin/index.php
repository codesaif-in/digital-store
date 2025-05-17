<?php
$page_title = "Dashboard";
require_once('includes/admin_header.php'); 
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <?php
                $products = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
                $products = mysqli_fetch_assoc($products);
                ?>
                <h5 class="card-title">Products</h5>
                <h2><?= $products['count'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <?php
                $orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
                $orders = mysqli_fetch_assoc($orders);
                ?>
                <h5 class="card-title">Total Orders</h5>
                <h2><?= $orders['count'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <?php
                $pending = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE is_verified = 0");
                $pending = mysqli_fetch_assoc($pending);
                ?>
                <h5 class="card-title">Pending Orders</h5>
                <h2><?= $pending['count'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <?php
                $users = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
                $users = mysqli_fetch_assoc($users);
                ?>
                <h5 class="card-title">Users</h5>
                <h2><?= $users['count'] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Income and Activity Stats -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <?php
                $income = mysqli_query($conn, "
                    SELECT SUM(p.price) as total 
                    FROM orders o
                    JOIN products p ON o.product_id = p.id
                    WHERE o.is_verified = 1
                ");
                $income = mysqli_fetch_assoc($income);
                ?>
                <h2 class="text-success">₹<?= number_format($income['total'] ?? 0, 2) ?></h2>
                <small class="text-muted">From verified orders only</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent User Activity</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <?php
                        $activity = mysqli_query($conn, "
                            SELECT u.name, u.email, MAX(o.created_at) as last_activity,
                                   COUNT(o.id) as total_orders
                            FROM users u
                            LEFT JOIN orders o ON u.email = o.email
                            GROUP BY u.id
                            ORDER BY last_activity DESC
                            LIMIT 3
                        ");
                        
                        while ($user = mysqli_fetch_assoc($activity)):
                        ?>
                        <tr>
                            <td><?= $user['name'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['total_orders'] ?> orders</td>
                            <td><?= $user['last_activity'] ? date('d M', strtotime($user['last_activity'])) : 'No activity' ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<h2 class="h4 mb-3">Recent Orders</h2>
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
                <th>Downloads</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent_orders = mysqli_query($conn, "
                SELECT o.*, p.title as product_name, p.price, p.file_url,
                       (SELECT COUNT(*) FROM downloads WHERE order_id = o.id) as download_count
                FROM orders o
                JOIN products p ON o.product_id = p.id
                ORDER BY o.created_at DESC LIMIT 5
            ");
            
            while ($order = mysqli_fetch_assoc($recent_orders)):
            ?>
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
                <td><?= $order['download_count'] ?></td>
                <td>
                    <a href="orders.php?action=view&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Download Statistics -->
<h2 class="h4 mt-5 mb-3">Download Statistics</h2>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Downloads</th>
                <th>Last Downloaded</th>
                <th>Popularity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $downloads = mysqli_query($conn, "
                SELECT p.title, 
                       COUNT(d.id) as download_count,
                       MAX(d.download_time) as last_download,
                       p.price
                FROM products p
                LEFT JOIN downloads d ON p.id = d.product_id
                GROUP BY p.id
                ORDER BY download_count DESC
                LIMIT 5
            ");
            
            while ($product = mysqli_fetch_assoc($downloads)):
            ?>
            <tr>
                <td><?= $product['title'] ?></td>
                <td><?= $product['download_count'] ?></td>
                <td><?= $product['last_download'] ? date('d M Y', strtotime($product['last_download'])) : 'Never' ?></td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?= min(100, $product['download_count'] * 10) ?>%;" 
                             aria-valuenow="<?= $product['download_count'] ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?= $product['download_count'] ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('includes/admin_footer.php'); ?>