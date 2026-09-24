<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
require __DIR__ . '/../includes/koneksi.php';

// =========================================================
// 1. BAB 5: PAGINATION & PENCARIAN SISI SERVER
// =========================================================
$perPage = 5; // Batas data per halaman
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    // Menghitung total data hasil pencarian
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    // Query data sesuai pencarian, dikombinasikan LIMIT & OFFSET
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    // Menghitung total seluruh data
    $totalRows = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();

    // Query semua data dengan LIMIT & OFFSET
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}

// Bind nilai integer untuk LIMIT dan OFFSET
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>

<section>
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
    <?php endif; ?>

    <!-- Form Pencarian Sisi Server (method="get") -->
    <form method="get" action="list.php" class="search-box">
        <label for="search-input">Cari Judul Buku</label>
        <input type="text" name="q" id="search-input" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik judul buku...">
        <button type="submit">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="list.php" style="margin-left: 10px;">Reset Filter</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Tanggal Ditambahkan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarBuku)): ?>
            <tr>
                <td colspan="6">Data buku tidak ditemukan.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($daftarBuku as $buku): ?>
                <tr>
                    <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                    <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                    <td><?php echo htmlspecialchars($buku['tahun']); ?></td>
                    <td><?php echo htmlspecialchars($buku['stok']); ?></td>
                    <td><?php echo htmlspecialchars($buku['tanggal_ditambahkan'] ?? ''); ?></td>
                    <td>
                        <!-- Link Edit membawa parameter ID di URL -->
                        <a href="edit.php?id=<?php echo $buku['id']; ?>" class="btn-edit">Edit</a>
                        
                        <!-- Form Hapus menggunakan method POST demi keamanan -->
                        <form class="form-hapus" method="post" action="hapus.php">
                            <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    <!-- 2. NAVIGASI PAGINATION -->
    <nav class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
               class="<?php echo $i === $page ? 'active' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </nav>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>