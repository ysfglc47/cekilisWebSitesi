<?php
session_start();
require 'baglan.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drawName = trim($_POST['draw_name']);
    $description = trim($_POST['description']);
    $creatorUsername = $_SESSION['user'];

    if (!empty($drawName) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO draws (draw_name, description, creator_username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $drawName, $description, $creatorUsername);

        if ($stmt->execute()) {
            $success = "Çekiliş başarıyla oluşturuldu!";
        } else {
            $error = "Çekiliş oluşturulurken bir hata oluştu: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Lütfen çekiliş adı ve açıklamasını girin!";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çekiliş Oluştur</title>
</head>
<body>
    <h1>Çekiliş Oluştur</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="draw_name">Çekiliş Adı:</label>
        <input type="text" name="draw_name" id="draw_name" required>
        <br>
        <label for="description">Açıklama:</label>
        <textarea name="description" id="description" rows="4" cols="50" required></textarea>
        <br>
        <button type="submit">Çekilişi Oluştur</button>
    </form>
    <a href="ana_menu.php">Ana Menüye Dön</a>
</body>
</html>