<?php
require __DIR__ . '/includes/koneksi.php';

// Path ke file buku.json milik Jobsheet 6
$jsonPath = __DIR__ . '/../jobsheet-6/data/buku.json';

// Cek apakah file JSON ada
if (!file_exists($jsonPath)) {
    die("File JSON tidak ditemukan di: " . $jsonPath);
}

// Baca isi file JSON dan ubah menjadi array PHP
$jsonContent = file_get_contents($jsonPath);
$bukuList = json_decode($jsonContent, true);

if (empty($bukuList)) {
    die("Data JSON kosong atau format tidak valid.");
}

// Siapkan query INSERT dengan prepared statement
$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$totalMigrasi = 0;

// Loop setiap data dari JSON dan masukkan ke PostgreSQL
foreach ($bukuList as $buku) {
    $stmt->execute([
        'judul'     => $buku['judul'] ?? '',
        'pengarang' => $buku['pengarang'] ?? '',
        'tahun'     => (int) ($buku['tahun'] ?? 0),
        'isbn'      => $buku['isbn'] ?? null,
        'stok'      => (int) ($buku['stok'] ?? 0),
        'kategori'  => $buku['kategori'] ?? null,
    ]);
    $totalMigrasi++;
}

echo "Migrasi berhasil! Total " . $totalMigrasi . " data buku dari JSON telah dipindahkan ke PostgreSQL.";
