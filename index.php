<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: report.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Report Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container0">
        <div class="title">
            <p>Welcome to</p>
            <h1>AMONG US REPORT</h1>
            <img src="image/report.jpg">
        </div>
            <div class="login">
                <h2>Login</h2>
                <form action="login.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <button type="submit">Login</button>
                </form>
            <p>Belum punya akun? <a href="register.php">Buat akun!</a></p>
        </div>
    </div>
</body>
</html>
