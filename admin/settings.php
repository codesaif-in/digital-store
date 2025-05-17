<?php
$page_title = "Settings";
require_once('includes/admin_header.php');

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current = sanitize($_POST['current_password']);
    $new = sanitize($_POST['new_password']);
    $confirm = sanitize($_POST['confirm_password']);
    
    // Verify current password (hardcoded for demo - replace with database check)
    if ($current != "admin123") {
        $error = "Current password is incorrect";
    } elseif ($new != $confirm) {
        $error = "New passwords don't match";
    } else {
        // In real app, you would update the password in database
        $_SESSION['message'] = "Password changed successfully";
        redirect('settings.php');
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Settings</h1>
</div>

<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success"><?= $_SESSION['message'] ?></div>
<?php unset($_SESSION['message']); endif; ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/admin_footer.php'); ?>