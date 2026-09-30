<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
$page_title = "Login";
include __DIR__ . '/../includes/header.php';
?>

<main>
    <h2>Login Petugas</h2>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?php 
            echo $_SESSION['flash']['pesan']; 
            unset($_SESSION['flash']);
            ?>
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
        <div style="margin-bottom: 1rem;">
            <label for="username" style="display: block; margin-bottom: .5rem;">Username</label>
            <input type="text" id="username" name="username" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="password" style="display: block; margin-bottom: .5rem;">Password</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 8px;">
        </div>
        <button type="submit" style="padding: 10px 15px; background-color: #0056b3; color: white; border: none; cursor: pointer;">Login</button>
    </form>
    
    <p style="margin-top: 1rem;">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>