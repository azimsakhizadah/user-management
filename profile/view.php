<?php
session_start();
require '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];
?>
<style>
    .profile-container {
    max-width: 500px;
    margin: 50px auto;
}

.profile-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    background-color: #fff;
}

.profile-image img {
    border: 1px solid #000000ff;
    transition: 0.3s ease;
}

.profile-image img:hover {
    transform: scale(1.05);
}

</style>
<div class="profile-container">
    <div class="card profile-card">
        <div class="card-body text-center">
            <div class="profile-image mb-3">
                <?php if ($user['profile_image']): ?>
                    <img src="../assets/images/<?= $user['profile_image'] ?>" alt="profile" class="rounded-circle shadow" width="100" height="100">
                <?php else: ?>
                    <img src="../assets/images/profile-icon.webp" alt=" no image " class="rounded-circle shadow" width="100">
                <?php endif; ?>
            </div>
            <h4 class="card-title"><?= htmlspecialchars($user['username']) ?></h4>
            <p class="card-text text-muted"> <?= htmlspecialchars($user['email']) ?></p>

            <div class="d-grid gap-2 mt-4">
                <a href="edit.php" class="btn btn-outline-primary"> Edit profile</a>
                <a href="../auth/logout.php" class="btn btn-outline-danger">logout</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
