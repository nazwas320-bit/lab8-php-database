
<?php
include("koneksi.php");

// Query untuk menampilkan data
$sql = "SELECT * FROM data_barang";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Data Barang</title>
</head>
<body>

<div class="container">
    <h1>Data Barang</h1>

    <div class="main">
        <table>
            <tr>
                <th>Gambar</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <img src="gambar/<?= htmlspecialchars($row['gambar']); ?>"
                             alt="<?= htmlspecialchars($row['nama']); ?>">
                    </td>

                    <td><?= htmlspecialchars($row['nama']); ?></td>

                    <td><?= htmlspecialchars($row['kategori']); ?></td>

                    <!-- Harga beli -->
                    <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>

                    <!-- Harga jual -->
                    <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.'); ?></td>

                    <td><?= $row['stok']; ?></td>

                    <td>
                        <a href="ubah.php?id=<?= $row['id_barang']; ?>">Ubah</a> |
                        <a href="hapus.php?id=<?= $row['id_barang']; ?>"
                           onclick="return confirm('Yakin ingin menghapus barang ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="7">Belum ada data barang.</td>
                </tr>
            <?php endif; ?>

        </table>
    </div>
</div>

</body>
</html>
