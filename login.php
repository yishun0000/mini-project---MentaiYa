<?php
/* =========================================
   1. SESSION & DB SETUP
   ========================================= */
session_start();
require_once __DIR__ . '/config/database.php';

$error = '';

/* =========================================
   2. AUTHENTICATION LOGIC
   ========================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user details by username
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify hashed password and assign session state
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
        ];

        // Redirect based on user role
        if ($user['role'] === 'Admin') {
            header("Location: admin.php");
            exit;
        } else if ($user['role'] === 'Staff') {
            header("Location: staff.php");
            exit;
        } else {
            header("Location: customer.php");
            exit;
        }
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MentaiYa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-card">
        <h1>MenTaiYa</h1>
        <h2>Login</h2>

        <?php if ($error !== '') { ?>
            <p class="msg-error" style="color: red"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="login.php">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button class="btn" type="submit" style="width: 100%;">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>