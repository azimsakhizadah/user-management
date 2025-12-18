<?php
session_start();
require '../includes/db.php';

// Only admin can delete users
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Get the user ID from URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Prevent admin from deleting themselves
    if ($id == $_SESSION['user']['id']) {
        echo "<div class='alert alert-danger text-center mt-3'>You cannot delete your own account.</div>";
        exit;
    }

    // Get user info to delete profile image
    $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
        // Delete profile image file if exists
        if ($user['profile_image'] && file_exists("../assets/images/".$user['profile_image'])) {
            unlink("../assets/images/".$user['profile_image']);
        }

        // Delete user from database
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Redirect back to user list
    header("Location: dashboard.php");
    exit;
} else {
    echo "<div class='alert alert-danger text-center mt-3'>Invalid user ID.</div>";
}
?>
