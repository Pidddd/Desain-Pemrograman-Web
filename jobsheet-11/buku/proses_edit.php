<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

// 1. Tangkap ID dari input hidden form edit
$id = $_POST['id'] ?? null;

// Jika ID tidak ada, kembalikan ke list.php
if (!$id) {
    header('Location: list.php');
    exit;
}

// 2. Tangkap field lainnya dari $_POST
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');

// 3. Validasi server-side (sama seperti proses_tambah.php)
$errors = [];
if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus di antara 1900-2026.";
}
if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}

if ($isbn !== '') {
    if (!preg_match('/^[0-9-]+$/', $isbn)) {
        $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung.";
    }
}

// Jika terdapat error validasi, kembalikan ke form edit.php sesuai ID buku
if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

// 4. Jalankan perintah UPDATE dengan klausa WHERE id = :id
$stmt = $pdo->prepare(
    "UPDATE buku 
     SET judul = :judul, 
         pengarang = :pengarang, 
         tahun = :tahun, 
         isbn = :isbn, 
         stok = :stok, 
         kategori = :kategori 
     WHERE id = :id"
);

$stmt->execute([
    'judul' => $judul,
    'pengarang' => $pengarang,
    'tahun' => (int) $tahun,
    'isbn' => $isbn,
    'stok' => (int) $stok,
    'kategori' => $kategori,
    'id' => $id,
]);

// 5. Set pesan sukses & arahkan kembali ke list.php
$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data buku berhasil diubah.'];
header('Location: list.php');
exit;