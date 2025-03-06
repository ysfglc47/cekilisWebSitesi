<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cekilis_sitesi";
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
else{
    echo "Bağlantı başarılı!"; 
}
?>