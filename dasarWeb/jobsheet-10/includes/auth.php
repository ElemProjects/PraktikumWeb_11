
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['simpus_remember'])) {
    require_once __DIR__ . '/koneksi.php';

    $token = $_COOKIE['simpus_remember'];
    $tokenHash = hash('sha256', $token);

    $stmt = $pdo->prepare(
        "SELECT u.id, u.nama, u.role
         FROM remember_tokens rt
         JOIN users u ON u.id = rt.user_id
         WHERE rt.token_hash = :token_hash
           AND rt.expires_at > CURRENT_TIMESTAMP"
    );

    $stmt->execute(['token_hash' => $tokenHash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_attempts'] = 0;
    }
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
