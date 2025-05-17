<?php
$page_title = "User Management";
require_once('includes/admin_header.php');

// Handle user actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $user_id = intval($_GET['id']);
    
    if ($action == 'delete') {
        mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
        $_SESSION['message'] = "User deleted successfully";
        redirect('users.php');
    }
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
</div>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?= $_SESSION['message'] ?></div>
<?php unset($_SESSION['message']); endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['phone'] ? $user['phone'] : '-' ?></td>
                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <a href="users.php?action=delete&id=<?= $user['id'] ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete this user?')">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('includes/admin_footer.php'); ?>