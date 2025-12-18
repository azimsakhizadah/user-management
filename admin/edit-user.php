<?php
session_start();
require '../includes/db.php';
include '../includes/header.php';

// Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Get user ID
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='alert alert-danger'>Invalid user ID.</div>";
    exit;
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $role       = $_POST['role'];
    $password   = $_POST['password'] ?? '';
    $imageName  = $user['profile_image'];

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $imageName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], "../assets/images/$imageName");
    }

    // Update query with or without password
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ?, profile_image = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $imageName, $hashedPassword, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $imageName, $id]);
    }

    // Redirect to dashboard after update
    header("Location: dashboard.php");
    exit;
}
?>

<!-- Page Design -->
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit User</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                    <?php if ($user['profile_image']): ?>
                        <img src="../assets/images/<?= $user['profile_image'] ?>" width="80" class="mt-2 rounded">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="dashboard.php" class="btn btn-secondary ms-2">Back to Dashboard</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
