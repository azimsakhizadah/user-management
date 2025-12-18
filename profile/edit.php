<?php
session_start();
require '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $imageName = $user['profile_image'];

    // Upload profile image
    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "../assets/images/";
        $imageName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            // Image uploaded successfully
        } else {
            echo "<div class='alert alert-danger text-center mt-3'> Image not uploaded! </div>";
        }
    }

    // database update
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$username, $email, $imageName, $user['id']]);

    // session update
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['profile_image'] = $imageName;

    // redirect to view profile
    header("Location: edit.php");
    exit();
}
?>


<style>
    .edit-profile-container {
    max-width: 500px;
    margin: 50px auto;
}

.edit-profile-card {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    background-color: #fff;
}

.edit-profile-card img {
    border: 1px solid #28a745;
    transition: 0.3s ease;
}

.edit-profile-card img:hover {
    transform: scale(1.05);
}

</style>


<div class="edit-profile-container">
    <div class="card edit-profile-card">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Edit Your Profile</h3>

            <div class="text-center mb-4">
                <?php if ($user['profile_image']): ?>
                    <img src="../assets/images/<?= $user['profile_image'] ?>" alt="profile" class="rounded-circle shadow" width="100" height="100">
                <?php else: ?>
                    <img src="../assets/images/profile-icon.webp" alt="No image " class="rounded-circle shadow" width="100">
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Username </label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Eamil</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile photo  </label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success"> Save changes</button>
                    <a href="view.php" class="btn btn-outline-secondary"> Return Back  </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
