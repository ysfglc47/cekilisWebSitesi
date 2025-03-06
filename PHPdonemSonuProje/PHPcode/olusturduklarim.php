<?php
session_start();
require 'baglan.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$stmt = $conn->prepare("SELECT * FROM draws WHERE creator_username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$result = $stmt->get_result();
$draws = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize_draw'])) {
    $drawId = intval($_POST['draw_id']);
    $winnerCount = intval($_POST['winner_count']);

    $stmt = $conn->prepare("SELECT participant_name FROM participants WHERE draw_id = ?");
    $stmt->bind_param("i", $drawId);
    $stmt->execute();
    $result = $stmt->get_result();
    $participants = $result->fetch_all(MYSQLI_ASSOC);

    $participantNames = array_column($participants, 'participant_name');

    if ($winnerCount > 0 && $winnerCount <= count($participantNames)) {
        $winnerIndexes = array_rand($participantNames, $winnerCount);
        $winnerIndexes = (array) $winnerIndexes;
        $winnerNames = array_map(fn($index) => $participantNames[$index], $winnerIndexes);
        foreach ($winnerNames as $winner) {
            $stmt = $conn->prepare("INSERT INTO winners (draw_id, winner_name) VALUES (?, ?)");
            $stmt->bind_param("is", $drawId, $winner);
            $stmt->execute();
        }
        $success = "Kazananlar: " . implode(", ", $winnerNames);
    } else {
        $error = "Kazanan kişi sayısı, katılımcı sayısından fazla olamaz!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oluşturduklarım</title>
</head>
<body>
    <h1>Oluşturduklarım</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (count($draws) > 0): ?>
        <ul>
            <?php foreach ($draws as $draw): ?>
                <li>
                    <strong><?php echo htmlspecialchars($draw['draw_name']); ?></strong> - 
                    <?php echo htmlspecialchars($draw['description']); ?> (Oluşturulma: <?php echo $draw['created_at']; ?>)
                    <form action="" method="post" style="margin-top: 10px;">
                        <input type="hidden" name="draw_id" value="<?php echo $draw['id']; ?>">
                        <label for="winner_count_<?php echo $draw['id']; ?>">Kazanan Sayısı:</label>
                        <input type="number" name="winner_count" id="winner_count_<?php echo $draw['id']; ?>" min="1" required>
                        <button type="submit" name="finalize_draw">Sonuçlandır</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Henüz bir çekiliş oluşturmadınız.</p>
    <?php endif; ?>
    <a href="ana_menu.php">Ana Menüye Dön</a>
</body>
</html>