<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $query = "SELECT * FROM pasien WHERE nama_pasien LIKE '%$search%' OR nomor_rm LIKE '%$search%' ORDER BY id_pasien DESC";
} else {
    $query = "SELECT * FROM pasien ORDER BY id_pasien DESC";
}
$result = mysqli_query($koneksi, $query);

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pasien WHERE id_pasien = $id");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Rekam Medis Klinik</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #8a2be2; color: white; }
        .btn { padding: 8px 12px; text-decoration: none; color: white; border-radius: 4px; font-size: 13px; }
        .btn-add { background-color: #28a745; }
        .btn-view { background-color: #007bff; }
        .btn-delete { background-color: #dc3545; }
        .search-box { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Data Manajemen Pasien Klinik</h2>
    <hr>
    <p>Halo, <?= $_SESSION['nama_petugas']; ?>! | <a href="logout.php" style="color: red; text-decoration: none; font-weight: bold;">[ Logout ]</a></p>
    
    <div class="search-box">
        <a href="tambah_pasien.php" class="btn btn-add">+ Tambah Pasien Baru</a>
        
        <form action="index.php" method="GET" style="display:inline; float:right;">
            <input type="text" name="search" placeholder="Cari nama / No. RM..." value="<?= htmlspecialchars($search); ?>" style="padding: 7px; width: 250px;">
            <button type="submit" style="padding: 7px;">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>L/P</th>
                <th>Tanggal Lahir</th>
                <th>No. Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><strong><?= $row['nomor_rm']; ?></strong></td>
                    <td><?= $row['nama_pasien']; ?></td>
                    <td><?= $row['gender']; ?></td>
                    <td><?= $row['tgl_lahir']; ?></td>
                    <td><?= $row['no_telp']; ?></td>
                    <td>
                        <a href="detail_rm.php?id=<?= $row['id_pasien']; ?>" class="btn btn-view">Rekam Medis</a>
                        <a href="index.php?hapus=<?= $row['id_pasien']; ?>" class="btn btn-delete" onclick="return confirm('Hapus pasien ini beserta semua rekam medisnya?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Data pasien tidak ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>