<?php
require_once('includes/db.php');
require_once('includes/functions.php');

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$product_id = intval($_GET['id']);
$product = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
if (mysqli_num_rows($product) == 0) redirect('index.php');
$product = mysqli_fetch_assoc($product);

// Get related products
$related = mysqli_query($conn, "SELECT * FROM products WHERE id != $product_id LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['title']) ?> | DigitalStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-gallery img {
            border-radius: 10px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .product-gallery img:hover {
            transform: scale(1.03);
        }
        .main-image {
            border: 1px solid #eee;
            border-radius: 10px;
        }
        .price-tag {
            font-size: 2rem;
            color: #28a745;
        }
        .features-list {
            list-style-type: none;
            padding-left: 0;
        }
        .features-list li {
            padding: 5px 0;
        }
        .features-list li::before {
            content: "✓";
            color: #28a745;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation (Same as index.php) -->
    <?php include('includes/header.php'); ?>

    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['title']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="main-image mb-4">
                    <img src="https://via.placeholder.com/600x400?text=Product+Image" class="img-fluid w-100" alt="<?= htmlspecialchars($product['title']) ?>">
                </div>
                <div class="row product-gallery">
                    <div class="col-3">
                        <img src="https://via.placeholder.com/150x100?text=Thumb+1" class="img-fluid" alt="Thumbnail 1">
                    </div>
                    <div class="col-3">
                        <img src="https://via.placeholder.com/150x100?text=Thumb+2" class="img-fluid" alt="Thumbnail 2">
                    </div>
                    <div class="col-3">
                        <img src="https://via.placeholder.com/150x100?text=Thumb+3" class="img-fluid" alt="Thumbnail 3">
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="mb-3"><?= htmlspecialchars($product['title']) ?></h1>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <span class="price-tag">₹<?= number_format($product['price'], 2) ?></span>
                    </div>
                    <div class="text-warning">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                        <span class="text-muted ms-2">(42 reviews)</span>
                    </div>
                </div>

                <p class="lead"><?= htmlspecialchars($product['description']) ?></p>

                <ul class="features-list mb-4">
                    <li>Instant digital download</li>
                    <li>Lifetime updates</li>
                    <li>24/7 support</li>
                    <li>Money back guarantee</li>
                </ul>

                <div class="d-flex gap-2 mb-4">
                    <div class="input-group" style="width: 120px;">
                        <button class="btn btn-outline-secondary" type="button">-</button>
                        <input type="text" class="form-control text-center" value="1">
                        <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                    <a href="checkout.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="fas fa-shopping-cart me-2"></i> Buy Now
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0 text-primary me-3">
                                <i class="fas fa-truck fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mt-0">Instant Delivery</h5>
                                <p class="mb-0">Get immediate access after payment verification</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Reviews</button>
                    </li>
                </ul>
                <div class="tab-content p-3 border border-top-0 rounded-bottom">
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <h4>Product Specifications</h4>
                        <p>This is a detailed description of the product features and specifications.</p>
                        <table class="table">
                            <tr>
                                <th>Format</th>
                                <td>PDF/ZIP</td>
                            </tr>
                            <tr>
                                <th>File Size</th>
                                <td>15.4 MB</td>
                            </tr>
                            <tr>
                                <th>Requirements</th>
                                <td>Any device with PDF reader</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <h4>Customer Reviews</h4>
                        <!-- Sample reviews -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <h5>Excellent Product!</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted">By Rahul on <?= date('M d, Y') ?></p>
                            <p>This product exceeded my expectations. The quality is amazing!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">You May Also Like</h3>
                <div class="row">
                    <?php while ($item = mysqli_fetch_assoc($related)): ?>
                    <div class="col-md-3">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/300x200?text=Related" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="text-success">₹<?= $item['price'] ?></p>
                                <a href="product.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (Same as index.php) -->
    <?php include('includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image gallery functionality
        document.querySelectorAll('.product-gallery img').forEach(img => {
            img.addEventListener('click', function() {
                document.querySelector('.main-image img').src = this.src;
            });
        });
    </script>
</body>
</html>