<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
    <h2>Tambah Buku</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php">
        <?php echo csrf_field(); ?>
        <div style="margin-bottom: 1rem;">
            <label for="judul">Judul</label><br>
            <input type="text" id="judul" name="judul" required>
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="pengarang">Pengarang</label><br>
            <input type="text" id="pengarang" name="pengarang" required>
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="tahun">Tahun Terbit</label><br>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="isbn">ISBN</label><br>
            <input type="text" id="isbn" name="isbn">
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="stok">Stok</label><br>
            <input type="number" id="stok" name="stok" min="0" required>
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="kategori">Kategori</label><br>
            <select id="kategori" name="kategori">
                <option value="fiksi">Fiksi</option>
                <option value="non-fiksi">Non-Fiksi</option>
                <option value="referensi">Referensi</option>
            </select>
        </div>
        <p>
            <button type="submit">Simpan</button>
        </p>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>