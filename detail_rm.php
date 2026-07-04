<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id_pasien = $_GET['id'];

$pasien_query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE id_pasien = $id_pasien");
$pasien = mysqli_fetch_assoc($pasien_query);

if (!$pasien) {
    echo "Data pasien tidak ditemukan!";
    exit;
}

if (isset($_POST['submit_rm'])) {
    $tgl_periksa = $_POST['tgl_periksa'];
    $keluhan     = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $diagnosis   = mysqli_real_escape_string($koneksi, $_POST['diagnosis']);
    $tindakan    = mysqli_real_escape_string($koneksi, $_POST['tindakan']);

    // Query simpan ke tabel rekam_medis
    $query_insert = "INSERT INTO rekam_medis VALUES (NULL, $id_pasien, '$tgl_periksa', '$keluhan', '$diagnosis', '$tindakan')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        // Refresh halaman agar rekam medis baru langsung muncul di daftar histori
        header("Location: detail_rm.php?id=$id_pasien");
        exit;
    } else {
        echo "Gagal menyimpan rekam medis: " . mysqli_error($koneksi);
    }
}

$rm_query = mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE id_pasien = $id_pasien ORDER BY tgl_periksa DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekam Medis - <?= htmlspecialchars($pasien['nama_pasien']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        .back-btn { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #8a2be2; font-weight: bold; }
        .box-pasien { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-top: 4px solid #8a2be2; }
        .box-pasien h3 { margin-top: 0; color: #333; }
        .flex-container { display: flex; gap: 30px; }
        .left-col { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .right-col { flex: 1.5; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; width: 100%; border-radius: 4px; font-size: 15px; }
        .card-rm { border: 1px solid #ddd; background: #fff; padding: 15px; margin-bottom: 15px; border-left: 5px solid #28a745; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .card-rm h4 { margin: 0 0 10px 0; color: #28a745; }
        .card-rm p { margin: 5px 0; font-size: 14px; line-height: 1.4; }
    </style>
</head>
<body>

    <a href="index.php" class="back-btn">← Kembali ke Daftar Pasien</a>
    <h2>E-Rekam Medis Pasien</h2>
    <hr>

    <div class="box-pasien">
        <h3>Identitas Pasien</h3>
        <p><strong>No. RM:</strong> <?= htmlspecialchars($pasien['nomor_rm']); ?> &nbsp;|&nbsp; <strong>Nama Pasien:</strong> <?= htmlspecialchars($pasien['nama_pasien']); ?> (<?= $pasien['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?>)</p>
        <p><strong>Tanggal Lahir:</strong> <?= $pasien['tgl_lahir']; ?> &nbsp;|&nbsp; <strong>No. Telp:</strong> <?= htmlspecialchars($pasien['no_telp']); ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($pasien['alamat']); ?></p>
    </div>

    <div class="flex-container">
        
        <div class="left-col">
            <h3>Pemeriksaan Baru</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Tanggal Periksa</label>
                    <input type="date" name="tgl_periksa" value="<?= date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Keluhan Pasien</label>
                    <textarea name="keluhan" rows="3" placeholder="Tulis keluhan utama pasien..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Diagnosis Medis</label>
                    <textarea name="diagnosis" rows="3" placeholder="Hasil pemeriksaan fisik/diagnosis penyakit..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Tindakan / Resep Obat</label>
                    <textarea name="tindakan" rows="3" placeholder="Tindakan medis atau terapi obat yang diberikan..." required></textarea>
                </div>
                
                <button type="submit" name="submit_rm" class="btn-submit">Simpan Rekam Medis</button>
            </form>
        </div>

        <div class="right-col">
            <h3>Histori Kunjungan & Rekam Medis</h3>
            
            <?php if (mysqli_num_rows($rm_query) > 0): ?>
                <?php while($rm = mysqli_fetch_assoc($rm_query)): ?>
                    <div class="card-rm">
                        <h4>Tanggal Kunjungan: <?= date('d F Y', strtotime($rm['tgl_periksa'])); ?></h4>
                        <p><strong>Keluhan:</strong> <br><?= nl2br(htmlspecialchars($rm['keluhan'])); ?></p>
                        <p><strong>Diagnosis:</strong> <br><?= nl2br(htmlspecialchars($rm['diagnosis'])); ?></p>
                        <p><strong>Tindakan/Obat:</strong> <br><?= nl2br(htmlspecialchars($rm['tindakan'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="background: #fff; padding: 20px; text-align: center; border: 1px dashed #ccc; color: #666; border-radius: 4px;">
                    Belum ada riwayat rekam medis untuk pasien ini.
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>