<?php
$page_title = "Product Management";
require_once('includes/admin_header.php');

// Handle product actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = intval($_GET['id']);
    
    if ($action == 'delete') {
        mysqli_query($conn, "DELETE FROM products WHERE id = $product_id");
        $_SESSION['message'] = "Product deleted successfully";
        redirect('products.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $price = floatval($_POST['price']);
    $file = $_FILES['file'];
    
    // File upload
    $file_url = '';
    if ($file['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $filename = time() . '_' . basename($file['name']);
        $target_file = $upload_dir . $filename;
        move_uploaded_file($file['tmp_name'], $target_file);
        $file_url = 'uploads/products/' . $filename;
    }
    
    if (isset($_POST['edit_id'])) {
        // Update product
        $product_id = intval($_POST['edit_id']);
        $query = "UPDATE products SET 
                 title = '$title',
                 description = '$description',
                 price = $price";
        if (!empty($file_url)) $query .= ", file_url = '$file_url'";
        $query .= " WHERE id = $product_id";
        
        mysqli_query($conn, $query);
        $_SESSION['message'] = "Product updated successfully";
    } else {
        // Add new product
        mysqli_query($conn, "INSERT INTO products (title, description, price, file_url) 
                            VALUES ('$title', '$description', $price, '$file_url')");
        $_SESSION['message'] = "Product added successfully";
    }
    
    redirect('products.php');
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Product Management</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="bi bi-plus"></i> Add Product
    </button>
</div>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?= $_SESSION['message'] ?></div>
<?php unset($_SESSION['message']); endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= $product['title'] ?></td>
                <td><?= substr($product['description'], 0, 50) ?>...</td>
                <td>₹<?= number_format($product['price'], 2) ?></td>
                <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary edit-product" 
                                data-id="<?= $product['id'] ?>"
                                data-title="<?= htmlspecialchars($product['title']) ?>"
                                data-description="<?= htmlspecialchars($product['description']) ?>"
                                data-price="<?= $product['price'] ?>">
                            Edit
                        </button>
                        <a href="products.php?action=delete&id=<?= $product['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this product?')">
                            Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Product Title</label>
                        <input type="text" class="form-control" name="title" id="productTitle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="productDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="productPrice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product File</label>
                        <input type="file" class="form-control" name="file" id="productFile">
                        <small class="text-muted">Leave empty to keep existing file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle edit button clicks
document.querySelectorAll('.edit-product').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Edit Product';
        document.getElementById('editId').value = this.dataset.id;
        document.getElementById('productTitle').value = this.dataset.title;
        document.getElementById('productDescription').value = this.dataset.description;
        document.getElementById('productPrice').value = this.dataset.price;
        
        var modal = new bootstrap.Modal(document.getElementById('addProductModal'));
        modal.show();
    });
});

// Reset modal when closed
document.getElementById('addProductModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalTitle').textContent = 'Add New Product';
    document.getElementById('editId').value = '';
    document.getElementById('productTitle').value = '';
    document.getElementById('productDescription').value = '';
    document.getElementById('productPrice').value = '';
    document.getElementById('productFile').value = '';
});
</script>

<?php require_once('includes/admin_footer.php'); ?>