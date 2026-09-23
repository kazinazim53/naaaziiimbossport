<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: messages.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "Admin" && $password === "12345678nazim") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: messages.php");
        exit;
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 p-4">
                <h3 class="fw-bold text-center mb-4"><i class="fas fa-lock text-primary me-2"></i>Admin Login</h3>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="It's you" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="blank" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>