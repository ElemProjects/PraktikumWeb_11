
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

// Hapus token Remember Me dari database
if (isset($_COOKIE['simpus_remember'])) {
    $tokenHash = hash(
        'sha256',
        $_COOKIE['simpus_remember']
    );

    $stmt = $pdo->prepare(
        "DELETE FROM remember_tokens WHERE token_hash = :token_hash"
    );
    $stmt->execute(['token_hash' => $tokenHash]);
}

// Hapus cookie Remember Me
setcookie('simpus_remember', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Hapus seluruh data session
$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
