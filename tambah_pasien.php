<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if (isset($_POST['submit'])) {
   
    $nomor_rm    = mysqli_real_escape_string($koneksi, $_POST['nomor_rm']);
    $nama_pasien = mysqli_real_escape_string($koneksi, $_POST['nama_pasien']);
    $tgl_lahir   = $_POST['tgl_lahir'];
    $gender      = $_POST['gender'];
    $alamat      = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telp     = mysqli_real_escape_string($koneksi, $_POST['no_telp']);

    $query = "INSERT INTO pasien VALUES (NULL, '$nomor_rm', '$nama_pasien', '$tgl_lahir', '$gender', '$alamat', '$no_telp')";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pasien</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; }
        .container { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); width: 400px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { padding: 10px 15px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; width: 100%; font-size: 15px; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #8a2be2; }
    </style>
</head>
<body>

    <div class="container">
        <a href="index.php" class="back-link">← Kembali ke Dashboard</a>
        <h2>Tambah Pasien Baru</h2>
        <hr style="margin-bottom: 20px;">

        <form action="" method="POST">
            <div class="form-group">
                <label>Nomor Rekam Medis (RM)</label>
                <input type="text" name="nomor_rm" placeholder="Contoh: RM-001" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_pasien" placeholder="Nama pasien" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="gender" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" required>
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telp" placeholder="Contoh: 081234xxxx" required>
            </div>
            <div class="form-group">
                <label>Alamat Rumah</label>
                <textarea name="alamat" rows="3" placeholder="Alamat lengkap" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn-submit">Simpan Data Pasien</button>
        </form>
    </div>

</body>
</html>