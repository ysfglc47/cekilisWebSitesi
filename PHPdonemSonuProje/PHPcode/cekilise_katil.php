<?php
session_start();
require 'baglan.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw_name'])) {
    $drawName = trim($_POST['draw_name']);
    $participantName = $_SESSION['user'];

    if (!empty($drawName)) {
        $stmt = $conn->prepare("SELECT * FROM draws WHERE draw_name = ?");
        $stmt->bind_param("s", $drawName);
        $stmt->execute();
        $result = $stmt->get_result();
        $draw = $result->fetch_assoc();

        if ($draw) {
            $stmt = $conn->prepare("SELECT * FROM participants WHERE draw_id = ? AND participant_name = ?");
            $stmt->bind_param("ss", $draw['id'], $participantName);
            $stmt->execute();
            $participantResult = $stmt->get_result();
            if ($participantResult->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO participants (draw_id, participant_name) VALUES (?, ?)");
                $stmt->bind_param("ss", $draw['id'], $participantName);
                if ($stmt->execute()) {
                    $success = "Çekilişe başarıyla katıldınız!";
                } else {
                    $error = "Katılım sırasında bir hata oluştu: " . $stmt->error;
                }
            } else {
                $error = "Bu çekilişe zaten katıldınız!";
            }
        } else {
            $error = "Bu isimde bir çekiliş bulunamadı!";
        }
        $stmt->close();
    } else {
        $error = "Lütfen geçerli bir çekiliş ismi girin!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çekilişe Katıl</title>
</head>
<body>
    <h1>Çekilişe Katıl</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="draw_name">Çekiliş İsmi:</label>
        <input type="text" name="draw_name" id="draw_name" required>
        <button type="submit">Katıl</button>
    </form>
    <a href="ana_menu.php">Ana Menüye Dön</a>
</body>
</html>