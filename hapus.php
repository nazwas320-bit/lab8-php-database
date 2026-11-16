
<?php
include 'koneksi.php';

// Memeriksa apakah ID tersedia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Menghapus data berdasarkan ID
$query = "DELETE FROM data_barang WHERE id_barang = ?";
$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
?>
