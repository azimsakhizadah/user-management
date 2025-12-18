<?php
session_start();
require '../includes/db.php';
include '../includes/header.php';

// Only allow admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Search functionality
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$users = $stmt->fetchAll();
?>

<!-- Admin Profile Header -->
<div class="d-flex justify-content-between align-items-center p-3 mb-4 bg-light border rounded">
    <div class="d-flex align-items-center">
        <?php if ($_SESSION['user']['profile_image'] && file_exists("../assets/images/".$_SESSION['user']['profile_image'])): ?>
            <img src="../assets/images/<?= htmlspecialchars($_SESSION['user']['profile_image']) ?>" class="rounded-circle me-3" width="60" height="60">
        <?php else: ?>
            <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-3" style="width:60px; height:60px;">
                <?= strtoupper(substr($_SESSION['user']['username'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div>
            <h5 class="mb-0"><?= htmlspecialchars($_SESSION['user']['username']) ?></h5>
            <small class="text-muted"><?= htmlspecialchars($_SESSION['user']['email']) ?></small>
        </div>
    </div>
    <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
</div>

<!-- Page Title -->
<h2 class="mb-4">User Management Panel</h2>

<!-- Search Form -->
<form method="GET" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?= htmlspecialchars($search) ?>">
</form>

<!-- Users Table -->
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Profile Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <?php if ($u['profile_image'] && file_exists("../assets/images/".$u['profile_image'])): ?>
                    <img src="../assets/images/<?= htmlspecialchars($u['profile_image']) ?>" width="50" class="rounded">
                <?php else: ?>
                    <span class="text-muted">None</span>
                <?php endif; ?>
            </td>
            <td>
                <!-- Edit User Button -->
                <a href="edit-user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>

                <!-- Delete User Button -->
                <a href="delete-user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
