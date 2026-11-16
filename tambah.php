
<?php
include 'koneksi.php';

$pesan = "";

if (isset($_POST['simpan'])) {

    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $ekstensi = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ekstensi, $ekstensi_valid)) {
        $pesan = "Format gambar harus JPG, JPEG, PNG, atau WEBP!";
    } else {

        $nama_gambar = uniqid('barang_') . '.' . $ekstensi;
        $folder = "gambar/" . $nama_gambar;

        if (move_uploaded_file($tmp, $folder)) {

            $query = "INSERT INTO data_barang
            (kategori, nama, gambar, harga_beli, harga_jual, stok)
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $query);

            mysqli_stmt_bind_param(
                $stmt,
                "sssddi",
                $kategori,
                $nama,
                $nama_gambar,
                $harga_beli,
                $harga_jual,
                $stok
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php");
                exit;
            } else {
                $pesan = "Gagal menyimpan data: " . mysqli_error($conn);
            }

        } else {
            $pesan = "Gagal mengupload gambar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Tambah Data Barang</h2>

    <?php if ($pesan != ""): ?>
        <p style="color:red; text-align:center;">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <label>Kategori Barang</label>
        <input type="text" name="kategori" required>

        <label>Nama Barang</label>
        <input type="text" name="nama" required>

        <label>Gambar Barang</label>
        <input type="file" name="gambar"
               accept=".jpg,.jpeg,.png,.webp" required>

        <label>Harga Beli</label>
        <input type="number" name="harga_beli" min="0" required>

        <label>Harga Jual</label>
        <input type="number" name="harga_jual" min="0" required>

        <label>Stok Barang</label>
        <input type="number" name="stok" min="0" required>

        <button type="submit" name="simpan"
                class="btn-tambah">
            Simpan Barang
        </button>

        <a href="index.php" class="btn btn-hapus">
            Kembali
        </a>

    </form>

</body>
</html>
