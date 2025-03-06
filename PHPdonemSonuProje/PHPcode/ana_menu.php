<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
    if (!isset($_SESSION['user'])) {
        header("Location: index.php");
        exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Menü</title>
</head>
<body>
    <h1>Hoş Geldiniz, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
    <h2>Ana Menü</h2>
    <ul>
        <li><a href="cekilise_katil.php">Çekilişe Katıl</a></li>
        <li><a href="katildiklarim.php">Katıldıklarım</a></li>
        <li><a href="olusturduklarim.php">Oluşturduklarım</a></li>
        <li><a href="cekilis_olustur.php">Çekiliş Oluştur</a></li>
        <li><a href="profil.php">Profil</a></li>
    </ul>
    <div style="text-align: right;">
        <?php if (isset($_SESSION['user'])): ?>
            <form action="" method="post" style="display: inline;">
                <span><h1>Çıkış için</h1> <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
                <button type="submit" name="logout">Çıkış Yap</button>
            </form>
            
        <?php endif; ?>
    </div>
</body>
</html>
