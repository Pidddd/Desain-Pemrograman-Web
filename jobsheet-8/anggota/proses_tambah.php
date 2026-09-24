<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if ($noHp !== '') {
    // Memastikan No HP hanya angka
    if (!preg_match('/^[0-9]+$/', $noHp)) {
        $errors[] = "No. HP hanya boleh berisi angka.";
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// SIMPAN KE POSTGRESQL MENGGUNAKAN PREPARED STATEMENT
$stmt = $pdo->prepare(
    "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
     VALUES (:nama, :no_anggota, :alamat, :no_hp)
     RETURNING id"
);

try {
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $noAnggota,
        'alamat'     => $alamat,
        'no_hp'      => $noHp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    // Tangkap error UNIQUE constraint dari PostgreSQL
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No. Anggota sudah terdaftar, gunakan nomor lain.'];
    header('Location: tambah.php');
    exit;
}