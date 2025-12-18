<?php
session_start();
require '../includes/db.php';  // fatal error, script stops here if db.php is missing
include '../includes/header.php';  // Warning, but script continues

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $redirect = $user['role'] === 'admin' ? '../admin/dashboard.php' : '../profile/view.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = "The login information is incorrect.";
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

.form-container {
    max-width: 600px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
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

</style>


<div class="form-container">
    <h2>Login to your account</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Please enter your email address" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="password" required>
        </div>
        <button type="submit" class="btn btn-success btn-custom mt-2">Register</button>
    </form>
    <div class="text-center mt-3">
        <p class="text-center mt-3 text-muted">Do not have account? <a href="register.php">Click here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
