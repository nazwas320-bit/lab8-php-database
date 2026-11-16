
<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "latihan1";
$port = 8889;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


?>
