
Aktor

• Tamu: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login.
• Petugas: login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman.
User Flow – Peminjaman Buku

[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"]
    -> [Pilih Anggota] -> [Pilih Buku (stok > 0)]
    -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard]


User Flow – Pengembalian Buku

[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (anggota/buku)]
    -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1]
    -> [Kembali ke Dashboard]

User Flow – Cek Tunggakan Anggota

[Petugas Login]
    -> [Dashboard]
    -> [Pilih Anggota]
    -> [Cari Anggota]
    -> [Lihat Peminjaman]
    -> [Cek Tunggakan]
    -> [Selesai]

Edge Case

1. Buku yang dipinjam stoknya 0.
   -> Peminjaman ditolak.

2. Anggota belum mengembalikan buku.
   -> Tidak dapat meminjam buku yang sama lagi.

3. Anggota tidak ditemukan.
   -> Sistem menampilkan pesan "Anggota tidak ditemukan".


Wireframe: Halaman Login

+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|          [ Login Petugas ]            |
|                                      |
|  Username : [______________]         |
|                                      |
|  Password : [______________]         |
|                                      |
|             [  Masuk  ]              |
|                                      |
|  Belum punya akun? Daftar di sini    |
+--------------------------------------+


Wireframe: Dashboard Petugas

+----------------------------------------------+
| SIMPUS-Mini    Beranda | Buku | Anggota |   |
| Peminjaman | (Nama Petugas) Logout           |
|----------------------------------------------|
| [Total Buku]   [Total Anggota]   [Sedang Dipinjam]
|                                              |
| Aksi Cepat:                                  |
| [ + Peminjaman Baru ]   [ + Pengembalian ]  |
|                                              |
| Transaksi Terbaru                            |
|----------------------------------------------|
| Anggota | Buku | Tgl Pinjam | Status        |
+----------------------------------------------+


Wireframe: Form Peminjaman

+----------------------------------+
| Form Peminjaman Buku             |
|----------------------------------|
| Anggota : [ dropdown pilih anggota ] |
| Buku    : [ dropdown, hanya stok>0 ] |
| Tanggal Pinjam : [ auto: hari ini ] |
|                                  |
|       [ Simpan Peminjaman ]      |
+----------------------------------+


Wireframe: Form Pengembalian

+----------------------------------+
| Pengembalian Buku                |
|----------------------------------|
| Cari transaksi aktif:            |
| [ nama anggota / judul buku ____ ] |
|                                  |
| Anggota | Buku | Tgl Pinjam | [Kembalikan] |
+----------------------------------+


Wireframe: Riwayat Peminjaman per Anggota

+----------------------------------+
| Riwayat Peminjaman - Siti Aminah |
|----------------------------------|
|                                  |
| Buku             | Pinjam | Kembali | Status |
| Laskar Pelangi   | 01/07  | 10/07   | Selesai |
| Bumi Manusia     | 15/07  |         | Dipinjam |
+----------------------------------+


Wireframe: Registrasi Anggota Baru

+------------------------------+
|      Registrasi Anggota      |
|------------------------------|
| Nama  : [______________]     |
| NIM   : [______________]     |
| Alamat: [______________]     |
|                              |
|        [ Daftar ]            |
+------------------------------+