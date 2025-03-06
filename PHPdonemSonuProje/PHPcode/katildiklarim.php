<?php
session_start();
require 'baglan.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$participantName = $_SESSION['user'];
$stmt = $conn->prepare("
    SELECT d.draw_name, d.description, d.created_at 
    FROM participants p
    JOIN draws d ON p.draw_id = d.id
    WHERE p.participant_name = ?
");
$stmt->bind_param("s", $participantName);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katıldığım Çekilişler</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Katıldığım Çekilişler</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong>Çekiliş İsmi:</strong> <?php echo htmlspecialchars($row['draw_name']); ?>
                    <br>
                    <strong>Açıklama:</strong> <?php echo htmlspecialchars($row['description']); ?>
                    <br>
                    <strong>Oluşturulma Tarihi:</strong> <?php echo htmlspecialchars($row['created_at']); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Henüz hiçbir çekilişe katılmadınız.</p>
    <?php endif; ?>
    <a href="ana_menu.php">Ana Sayfaya Dön</a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>