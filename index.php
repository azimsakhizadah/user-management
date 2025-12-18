<?php include "includes/header.php"; ?>

<div class="container-fluid">
    <div class="row g-0">
        <!-- Left Side -->
        <div class="col-md-6 left-side d-flex flex-column justify-content-center align-items-center">
            <h1>User Management<br>System</h1>
            <p>User management system designed by Mohammad Azim, built with PHP, MySQL, and Bootstrap. Tailored for both admins and regular users.</p>
        </div>

        <!-- Right Side -->
        <div class="col-md-6 d-flex justify-content-center align-items-center right-side bg-light">
            <div class="welcome-card">
                <h2>Welcome 👋</h2>
                <p>Start by registering a new account or logging in to manage your profile and access admin tools.</p>
                <a href="auth/register.php" class="btn btn-success btn-custom me-2">Register</a>
                <a href="auth/login.php" class="btn btn-primary btn-custom">Login</a>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
