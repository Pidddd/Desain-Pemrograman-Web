<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
</head>
<body style="padding: 2rem; font-family: monospace; background-color: #f5f6f8;">
    <h2 style="color: #1d5b8a;">Isi $_SESSION Saat Ini:</h2>
    <pre style="background: #2b2b2b; color: #a6e22e; padding: 1.5rem; border-radius: 8px; overflow-x: auto;">
<?php print_r($_SESSION); ?>
    </pre>
    <br>
    <a href="index.php" style="color: #1d5b8a; text-decoration: none; font-weight: bold;">&larr; Kembali ke Beranda</a>
</body>
</html>