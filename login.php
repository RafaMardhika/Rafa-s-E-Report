<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($username == "adminroom" && $email == "adminroom123@gmail.com" && $password == "adminroom") {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['admin'] = true;
        header("Location: report.php");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['admin'] = false;
                header("Location: report.php");
                exit();
            }
        }
        $stmt->close();
        echo "Invalid login";
    }
}
?>
