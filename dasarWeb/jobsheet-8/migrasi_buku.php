<?php

require __DIR__ . '/includes/koneksi.php';

$file = __DIR__ . '/data/buku.json';

if (!file_exists($file)) {
    die("File buku.json tidak ditemukan.");
}

$json = file_get_contents($file);
$data = json_decode($json, true);

if ($data === null) {
    die("JSON tidak valid.");
}

$stmt = $pdo->prepare(
    "INSERT INTO buku
    (judul, pengarang, tahun, stok, kategori)
    VALUES
    (:judul, :pengarang, :tahun, :stok, :kategori)"
);

$jumlah = 0;

foreach ($data as $buku) {
    $stmt->execute([
        'judul'     => $buku['judul'],
        'pengarang' => $buku['pengarang'],
        'tahun'     => $buku['tahun'],
        'stok'      => $buku['stok'],
        'kategori'  => $buku['kategori']
    ]);

    $jumlah++;
}

echo "Migrasi berhasil! $jumlah buku berhasil dimasukkan.";