<?php
require_once('includes/db.php');
require_once('includes/functions.php');

// Get featured products
$featured_products = mysqli_query($conn, "SELECT * FROM products WHERE is_featured = 1 LIMIT 8");

// Get new arrivals
$new_products = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC LIMIT 8");

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories LIMIT 6");

// Set page title
$page_title = "DigitalStore - Premium Digital Downloads";
require_once('includes/header.php');
?>

<!-- Hero Section -->
<section class="hero-section py-5 mb-5" style="background: linear-gradient(135deg, #6e8efb, #a777e3);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-white mb-3">Premium Digital Products</h1>
                <p class="lead text-white mb-4">Instant downloads • 24/7 support • Money-back guarantee</p>
                <div class="d-flex gap-3">
                    <a href="#products" class="btn btn-light btn-lg px-4">Browse Products</a>
                    <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4">How It Works</a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets/images/hero-image.png" alt="Digital Products" class="img-fluid d-none d-md-block">
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<div class="trust-badges py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3 text-center">
                <div class="p-3 bg-white rounded shadow-sm">
                    <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                    <h6 class="mb-0">Secure Payments</h6>
                </div>
            </div>
            <div class="col-6 col-md-3 text-center">
                <div class="p-3 bg-white rounded shadow-sm">
                    <i class="fas fa-download fa-2x text-primary mb-2"></i>
                    <h6 class="mb-0">Instant Delivery</h6>
                </div>
            </div>
            <div class="col-6 col-md-3 text-center">
                <div class="p-3 bg-white rounded shadow-sm">
                    <i class="fas fa-headset fa-2x text-primary mb-2"></i>
                    <h6 class="mb-0">24/7 Support</h6>
                </div>
            </div>
            <div class="col-6 col-md-3 text-center">
                <div class="p-3 bg-white rounded shadow-sm">
                    <i class="fas fa-undo fa-2x text-primary mb-2"></i>
                    <h6 class="mb-0">Money Back</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Shop by Category</h2>
            <a href="categories.php" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php while ($category = mysqli_fetch_assoc($categories)): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="category.php?id=<?= $category['id'] ?>" class="text-decoration-none">
                    <div class="card category-card h-100 border-0 shadow-sm hover-effect">
                        <div class="card-body text-center">
                            <div class="icon-wrapper mb-3 mx-auto rounded-circle bg-primary-light" style="width: 60px; height: 60px; line-height: 60px;">
                                <i class="fas <?= $category['icon'] ?> text-primary fa-lg"></i>
                            </div>
                            <h6 class="mb-0"><?= htmlspecialchars($category['name']) ?></h6>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="products" class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Featured Products</h2>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Sort By
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Popularity</a></li>
                    <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                    <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                    <li><a class="dropdown-item" href="#">Newest First</a></li>
                </ul>
            </div>
        </div>
        
        <div class="row g-4">
            <?php while ($product = mysqli_fetch_assoc($featured_products)): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <?php if ($product['is_featured']): ?>
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Featured</span>
                    <?php endif; ?>
                    <img src="<?= !empty($product['image_url']) ? $product['image_url'] : 'https://via.placeholder.com/300x200?text=Product' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['title']) ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary"><?= htmlspecialchars($product['category_name']) ?></span>
                            <div class="text-warning small">
                                <i class="fas fa-star"></i>
                                <?= number_format($product['rating'], 1) ?>
                            </div>
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                        <p class="card-text text-muted small"><?= substr(htmlspecialchars($product['description']), 0, 60) ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-success mb-0">₹<?= number_format($product['price'], 2) ?></span>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 text-center h-100">
                    <div class="card-body">
                        <div class="step-number mx-auto mb-3">1</div>
                        <h4 class="mb-3">Browse Products</h4>
                        <p class="mb-0">Explore our collection of premium digital products and find what you need.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 text-center h-100">
                    <div class="card-body">
                        <div class="step-number mx-auto mb-3">2</div>
                        <h4 class="mb-3">Make Payment</h4>
                        <p class="mb-0">Complete your purchase using secure UPI payment methods.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 text-center h-100">
                    <div class="card-body">
                        <div class="step-number mx-auto mb-3">3</div>
                        <h4 class="mb-3">Instant Download</h4>
                        <p class="mb-0">Get immediate access to your files after payment verification.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">New Arrivals</h2>
            <a href="products.php?sort=newest" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row g-4">
            <?php while ($product = mysqli_fetch_assoc($new_products)): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                    <img src="<?= !empty($product['image_url']) ? $product['image_url'] : 'https://via.placeholder.com/300x200?text=Product' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                        <p class="card-text text-muted small"><?= substr(htmlspecialchars($product['description']), 0, 60) ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-success mb-0">₹<?= number_format($product['price'], 2) ?></span>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-shopping-cart me-1"></i> Buy Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">What Our Customers Say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text">"Got my ebook immediately after payment. The quality exceeded my expectations!"</p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle me-2" width="40" alt="Customer">
                            <div>
                                <h6 class="mb-0">Rahul Sharma</h6>
                                <small class="text-muted">Purchased: PHP Masterclass</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add 2 more testimonials -->
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-3">Get Exclusive Deals</h2>
                <p class="lead mb-4">Subscribe to our newsletter and get 10% off your first purchase</p>
                <form class="row g-2 justify-content-center">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Your email address">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-light btn-lg w-100">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once('includes/footer.php'); ?>

<style>
.hero-section {
    border-radius: 0 0 20px 20px;
}
.trust-badges {
    margin-top: -30px;
    position: relative;
    z-index: 10;
}
.category-card {
    transition: all 0.3s ease;
}
.category-card:hover {
    transform: translateY(-5px);
}
.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.step-number {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
    margin: 0 auto 1rem;
}
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}
</style>