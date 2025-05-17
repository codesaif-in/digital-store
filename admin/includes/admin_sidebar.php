<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>" href="products.php">
                    <i class="bi bi-box-seam me-2"></i>
                    Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>" href="orders.php">
                    <i class="bi bi-cart-check me-2"></i>
                    Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>" href="users.php">
                    <i class="bi bi-people me-2"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>" href="settings.php">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
</nav>