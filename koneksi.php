<?php
$host = "sql202.infinityfree.com";
$user = "if0_42332209";             
$pass = "m0L82SKgsg";     
$db   = "if0_42332209_rekammedis"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>