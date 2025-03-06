<?php
session_start();
require 'baglan.php';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Bu kullanıcı adı zaten kullanımda. Lütfen başka bir kullanıcı adı seçin.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Bu e-posta adresi zaten kayıtlı.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                if ($stmt->execute()) {
                    $success = "Kayıt başarılı! Şimdi giriş yapabilirsiniz.";
                } else {
                    $error = "Kayıt sırasında bir hata oluştu.";
                }
            }
        }
    } else {
        $error = "Geçerli bir kullanıcı adı, e-posta adresi ve şifre girin.";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "E-posta adresi veya şifre hatalı.";
        }
    } else {
        $error = "Geçerli bir e-posta adresi ve şifre girin.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kura Çekimi</title>
</head>
<body>
    <div style="text-align: right;">
        <?php if (isset($_SESSION['user'])): ?>
            <form action="" method="post" style="display: inline;">
                <span>Hoş Geldiniz, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
                <button type="submit" name="logout">Çıkış Yap</button>
                <a href="ana_menu.php"><button type="button">Ana Menü</button></a>
            </form>
        <?php else: ?>
            <button onclick="document.getElementById('loginForm').style.display='block';">Giriş Yap</button>
            <button onclick="document.getElementById('registerForm').style.display='block';">Kayıt Ol</button>
        <?php endif; ?>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kura Çekimi</title>
</head>
<body>
    <div id="loginForm" style="display: none; border: 1px solid black; padding: 10px; margin: 10px;">
        <h2>Giriş Yap</h2>
        <form action="" method="post">
            <label for="email">E-posta:</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Şifre:    </label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit" name="login">Giriş Yap</button>
        </form>
    </div>
    <div id="registerForm" style="display: none; border: 1px solid black; padding: 10px; margin: 10px;">
        <h2>Kayıt Ol</h2>
        <form action="" method="post">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="email">E-posta:</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Şifre:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit" name="register">Kayıt Ol</button>
        </form>
    </div>
    <h1>Kura Çekimi</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <textarea name="names" rows="10" cols="30" placeholder="Kura listesini girin (her satıra bir isim yazın)"></textarea>
        <br>
        <input type="number" name="winner_count" min="1" placeholder="Kazanan kişi sayısı" required>
        <br>
        <button type="submit" name="draw">Kura Çek</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw'])) {
        $names = array_filter(array_map('trim', explode("\n", $_POST['names'])));
        $winnerCount = intval($_POST['winner_count']);
        if (count($names) > 0 && $winnerCount > 0) {
            if ($winnerCount <= count($names)) {
                $winners = array_rand($names, $winnerCount);
                $winners = (array) $winners;
                echo "<p>Kazananlar:</p><ul>";
                foreach ($winners as $index) {
                    echo "<li>" . htmlspecialchars($names[$index]) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: red;'>Kazanan kişi sayısı, listedeki kişi sayısından fazla olamaz!</p>";
            }
        } else {
            echo "<p style='color: red;'>Lütfen geçerli bir isim listesi ve kazanan kişi sayısı girin!</p>";
        }
    }
    ?>