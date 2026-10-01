<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

// --- 1. INISIALISASI & CEK LIMIT PERCOBAAN LOGIN ---
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 3) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Akun diblokir sementara karena terlalu banyak percobaan gagal.'];
    header('Location: login.php');
    exit;
}
// ---------------------------------------------------

// Pastikan data dikirim melalui metode POST
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Cek apakah input kosong
if ($username === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username dan password wajib diisi.'];
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // --- 2. RESET HITUNGAN JIKA LOGIN SUKSES ---
        $_SESSION['login_attempts'] = 0;
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if (isset($_POST['remember'])) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
        }
        // Redirect ke beranda jika sukses
        header('Location: ../index.php');
        exit;
    } else {
        // --- 3. TAMBAH HITUNGAN JIKA LOGIN GAGAL ---
        $_SESSION['login_attempts']++;
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username atau password salah.'];
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    // Menangkap error jika koneksi atau query database bermasalah
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()];
    header('Location: login.php');
    exit;
}