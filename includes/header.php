<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'DigitalStore - Trusted Digital Downloads' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store me-2"></i>DigitalStore
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown">Categories</a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <li><a class="dropdown-item" href="#">eBooks</a></li>
                            <li><a class="dropdown-item" href="#">Templates</a></li>
                            <li><a class="dropdown-item" href="#">Courses</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex">
                    <form class="d-flex me-3" action="search.php" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." name="q">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-light position-relative me-2" id="cartDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="cartDropdown">
                            <li><h6 class="dropdown-header">Your Cart</h6></li>
                            <?php if (empty($_SESSION['cart'])): ?>
                                <li><a class="dropdown-item text-muted" href="#">Your cart is empty</a></li>
                            <?php else: ?>
                                <!-- Cart items would be listed here -->
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-primary" href="../cart.php">View Cart</a></li>
                        </ul>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a href="#" class="btn btn-outline-light dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><h6 class="dropdown-header">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h6></li>
                                <li><a class="dropdown-item" href="../account.php"><i class="fas fa-user me-1"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="../downloads.php"><i class="fas fa-download me-1"></i> Downloads</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content will be inserted here -->
    <main class="flex-shrink-0">