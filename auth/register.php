<?php
require '../includes/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        // successfully, redirect to view.php
        header("Location: ../profile/view.php");
        exit(); // prevent further script execution
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center mt-3'> Registration failed: " . $e->getMessage() . "</div>";
    }
}
?>


<style>
    body {
        background: linear-gradient(135deg, #007bff, #6610f2);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }

    .register-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2.5rem 2rem;
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.2);
        width: 100%;
    }

    .register-card h2 {
        font-weight: 700;
        color: #343a40;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .btn-custom {
        width: 100%;
        padding: 0.75rem;
        font-size: 1.1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0px 0.5rem 1.5rem rgba(0,0,0,0.2);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="register-card">
    <h2>Register</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-success btn-custom mt-2">Register</button>
    </form>
    <p class="text-center mt-3 text-muted">Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include '../includes/footer.php'; ?>
