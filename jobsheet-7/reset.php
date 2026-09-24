<?php
session_start();
session_destroy(); // Menghapus semua isi session dari memori server
header("Location: index.php"); // Melempar user kembali ke halaman Beranda
exit;
?>