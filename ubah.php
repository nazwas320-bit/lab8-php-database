
<?php
include 'koneksi.php';

$pesan = "";

// Mengambil ID barang dari URL
if (!isset($_GET['id'])) {
    die("ID barang tidak ditemukan!");
}

$id = (int) $_GET['id'];

// Mengambil data barang berdasarkan ID
$query = mysqli_prepare($conn, "SELECT * FROM data_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data barang tidak ditemukan!");
}

// Proses menyimpan perubahan
if (isset($_POST['ubah'])) {

    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    $harga_beli = str_replace('.', '', $_POST['harga_beli']);
    $harga_jual = str_replace('.', '', $_POST['harga_jual']);

    $stok = (int) $_POST['stok'];

    // Menggunakan gambar lama jika tidak memilih gambar baru
    $nama_gambar = $data['gambar'];

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
            $pesan = "Gagal mengupload gambar!";
        } else {

            $gambar = $_FILES['gambar']['name'];
            $tmp = $_FILES['gambar']['tmp_name'];

            $ekstensi = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
            $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ekstensi, $ekstensi_valid)) {
                $pesan = "Format gambar harus JPG, JPEG, PNG, atau WEBP!";
            } else {

                $nama_gambar = uniqid('barang_') . '.' . $ekstensi;
                $folder = "gambar/" . $nama_gambar;

                if (!move_uploaded_file($tmp, $folder)) {
                    $pesan = "Gagal mengupload gambar!";
                }
            }
        }
    }

    if ($pesan == "") {

        $sql = "UPDATE data_barang SET
                kategori = ?,
                nama = ?,
                gambar = ?,
                harga_beli = ?,
                harga_jual = ?,
                stok = ?
                WHERE id_barang = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssddii",
            $kategori,
            $nama,
            $nama_gambar,
            $harga_beli,
            $harga_jual,
            $stok,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Ubah Data Barang</h2>

    <?php if ($pesan != ""): ?>
        <p style="color:red; text-align:center;">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <label>Kategori Barang</label>
        <input type="text" name="kategori"
               value="<?= htmlspecialchars($data['kategori']) ?>"
               required>

        <label>Nama Barang</label>
        <input type="text" name="nama"
               value="<?= htmlspecialchars($data['nama']) ?>"
               required>

        <label>Gambar Barang</label>

        <p>Gambar saat ini:</p>

        <img src="gambar/<?= htmlspecialchars($data['gambar']) ?>"
             alt="Gambar barang"
             style="width:120px; height:120px; object-fit:contain;">

        <input type="file" name="gambar"
               accept=".jpg,.jpeg,.png,.webp">

        <small>Kosongkan jika tidak ingin mengganti gambar.</small>

        <label>Harga Beli (Rp)</label>
        <input type="text" name="harga_beli"
               value="<?= number_format($data['harga_beli'], 0, ',', '.') ?>"
               inputmode="numeric"
               oninput="formatHarga(this)" required>

        <label>Harga Jual (Rp)</label>
        <input type="text" name="harga_jual"
               value="<?= number_format($data['harga_jual'], 0, ',', '.') ?>"
               inputmode="numeric"
               oninput="formatHarga(this)" required>

        <label>Stok Barang</label>
        <input type="number" name="stok"
               value="<?= $data['stok'] ?>"
               min="0" required>

        <button type="submit" name="ubah" class="btn-tambah">
            Simpan Perubahan
        </button>

        <a href="index.php" class="btn btn-hapus">
            Kembali
        </a>

    </form>

    <script>
        function formatHarga(input) {
            let angka = input.value.replace(/\D/g, '');
            input.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>

</body>
</html>
