// Mengambil & menampilkan Daftar Buku secara asinkron dari data/buku.json
function muatDaftarBuku() {
    muatDataJSON("../data/buku.json", [
        "judul",
        "pengarang",
        "tahun",
        "stok",
        "kategori"
    ]);
}



document.addEventListener("DOMContentLoaded", function () {
    muatDaftarBuku();

    const btn = document.getElementById("btn-muat-ulang");

    if (btn) {
        btn.addEventListener("click", muatDaftarBuku);
    }
});