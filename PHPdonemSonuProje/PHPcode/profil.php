<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
require 'baglan.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if (!empty($username) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE username = ?");
        $stmt->bind_param("sss", $username, $email, $_SESSION['user']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['user'] = $username;
            $success = "Profil güncellendi.";
        } else {
            $error = "Profil güncellenemedi. Lütfen tekrar deneyin.";
        }
        $stmt->close();
    } else {
        $error = "Geçerli bir kullanıcı adı ve e-posta girin.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        session_destroy();
        header("Location: index.php");
        exit;
    } else {
        $error = "Profil silinemedi. Lütfen tekrar deneyin.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>
<body>
    <h1>Profil</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($_SESSION['user']); ?>" required><br>
        <label for="email">E-posta:</label>
        <input type="email" name="email" id="email" required><br>
        <button type="submit">Profil Güncelle</button>
       
    <form action="" method="post">
        <button type="submit" name="delete_profile" style="color: red;">Profili Sil</button>
    </form>
    
    <a href="ana_menu.php">Ana Menüye Dön</a>
    </form>
</body>
</html>