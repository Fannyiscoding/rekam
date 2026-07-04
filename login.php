<?php

session_start();

include 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login_btn'])) {
    if (!$koneksi) {
        $error = "Koneksi ke database gagal!";
    } else {
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);

        $query = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Set session tanda user berhasil login
            $_SESSION['login'] = true;
            $_SESSION['nama_petugas'] = $row['nama_petugas'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Rekam Medis</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 15px rgba(0,0,0,0.1); width: 320px; border-top: 5px solid #8a2be2; }
        .login-box h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn-login { width: 100%; padding: 10px; background: #8a2be2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .error-msg { color: red; font-size: 13px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Login Sistem</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login_btn" class="btn-login">Masuk</button>
        </form>
    </div>

</body>
</html>