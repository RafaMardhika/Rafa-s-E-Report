<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['admin']) {
    $result = $conn->query("SELECT * FROM reports");
} else {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM reports WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    if ($_SESSION['admin'] || (isset($_POST['username']) && $_POST['username'] == $_SESSION['username'])) {
        $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: report.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email']; // Pastikan email pengguna disimpan di session
    $stmt = $conn->prepare("INSERT INTO reports (username, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $message);
    $stmt->execute();
    $stmt->close();
    header("Location: report.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>All reports for find the Impostor</h2>
        <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        <?php if (!$_SESSION['admin']) { ?>
            <form action="report.php" method="POST">
                <textarea name="message" placeholder="Give a report! Who is the Impostor?!" required></textarea><br>
                <button type="submit">Submit</button>
            </form>
        <?php } ?>

        <form action="report.php" method="POST">
            <table>
                <tr>
                    <th>Message</th>
                    <?php if ($_SESSION['admin']) { ?>
                        <th>Username</th>
                        <th>Email</th>
                    <?php } ?>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['message']; ?></td>
                        <?php if ($_SESSION['admin']) { ?>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                        <?php } ?>
                        <td>
                            <?php if ($_SESSION['admin'] || $row['username'] == $_SESSION['username']) { ?>
                                <button class="delete-btn" type="submit" name="delete_id" value="<?php echo $row['id']; ?>">Delete</button>
                                <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </form>
        <br>
    </div>
</body>
</html>
