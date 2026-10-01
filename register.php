<?php
session_start();

require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = 'Username is already taken.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $hashedPassword, 'Customer']);
        $_SESSION['user'] = [
            'id'       => $pdo->lastInsertId(),
            'username' => $username,
            'email'    => $email,
            'role'     => 'Customer',
        ];

        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MentaiYa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-card">
        <h1>MenTaiYa</h1>
        <h2>Register Customer Account</h2>

        <?php if ($error !== '') { ?>
            <p class="msg-error" style="color: red"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST" action="register.php">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Username / Full Name" required>

            <label>Email Address:</label>
            <input type="email" name="email" placeholder="example@gmail.com" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button class="btn" type="submit" style="width: 100%;">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>