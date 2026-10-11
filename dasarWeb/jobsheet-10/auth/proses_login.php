
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

$batas_login = 3;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Tolak jika batas percobaan sudah tercapai
if ($_SESSION['login_attempts'] >= $batas_login) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Terlalu banyak percobaan login gagal. Silakan mulai ulang sesi login.'
    ];

    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE username = :username"
);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Login berhasil
if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['login_attempts'] = 0;

    // Jika checkbox Ingat Saya dicentang
    if (isset($_POST['remember_me']) && $_POST['remember_me'] === '1') {
        // Buat token acak
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expires = time() + (7 * 24 * 60 * 60);

        // Hapus token lama milik pengguna ini
        $hapus = $pdo->prepare(
            "DELETE FROM remember_tokens WHERE user_id = :user_id"
        );
        $hapus->execute(['user_id' => $user['id']]);

        // Simpan hash token ke database
        $simpan = $pdo->prepare(
            "INSERT INTO remember_tokens (user_id, token_hash, expires_at)
             VALUES (:user_id, :token_hash, :expires_at)"
        );

        $simpan->execute([
            'user_id' => $user['id'],
            'token_hash' => $tokenHash,
            'expires_at' => date('Y-m-d H:i:s', $expires)
        ]);

        // Simpan token asli di cookie selama 7 hari
        setcookie('simpus_remember', $token, [
            'expires' => $expires,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS'])
                && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    } else {
        // Jika tidak memilih Ingat Saya, hapus cookie lama
        if (isset($_COOKIE['simpus_remember'])) {
            $tokenHash = hash(
                'sha256',
                $_COOKIE['simpus_remember']
            );

            $hapus = $pdo->prepare(
                "DELETE FROM remember_tokens WHERE token_hash = :token_hash"
            );
            $hapus->execute(['token_hash' => $tokenHash]);

            setcookie('simpus_remember', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
    }

    header('Location: ../index.php');
    exit;
}

// Login gagal
$_SESSION['login_attempts']++;

$sisa = $batas_login - $_SESSION['login_attempts'];

if ($sisa > 0) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => "Username atau password salah. Sisa percobaan: $sisa kali."
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Batas 3 percobaan login tercapai. Login ditolak.'
    ];
}

header('Location: login.php');
exit;
